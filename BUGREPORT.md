# Fehlerbericht: Viewer bleibt leer, wenn Contao die Skripte in den Seitenkopf stellt

**Gemeldet am:** 2026-08-08
**Gemeldet aus:** Sitzung zum BSV-Theme (`F:\Claude\BSV-Redesign\contao-theme-layout6`)
**Betrifft:** `src/ContentElements/PGNViewer.php`, Methode `addAssets()`

> **Erledigt in Fassung 2.0.3 (2026-08-08).** Der Befund war richtig und ließ
> sich in beiden Testinstallationen genau so nachstellen. Umgesetzt wurde
> allerdings nicht der vorgeschlagene Weg über `|defer`, sondern `TL_BODY` —
> Begründung und Messwerte unten unter „Nachtrag der betreuenden Sitzung“.
>
> Diese Datei bleibt als Beleg stehen; sie beschreibt den Stand vor der
> Behebung.

---

## Was zu sehen ist

Auf einer Seite mit dem Inhaltselement „PGN-Viewer" erscheint der Rahmen des
Viewers samt Schaltflächen, aber:

* **keine Figuren** auf dem Brett,
* **keine Partieauswahl**, obwohl die PGN-Datei mehrere Partien enthält,
* **kein Partiekopf**.

Beobachtet an: `https://contao57.berlinerschachverband.de/entry/christian-syre-gewinnt-das-29-emanuel-lasker-schnellturnier-in-thyrow.html`
(Contao 5.7, PHP 8.3, Bundle in der Fassung mit Chesstempo 2.5).

---

## Der Nachweis

Zwei Prüfseiten mit **denselben** Dateien dieses Bundles, **ohne** jedes
fremde Stylesheet, Unterschied ist einzig das Attribut `defer`:

| | Skripte im `<head>` ohne `defer` | dieselben mit `defer` |
| --- | ---: | ---: |
| Figurenelemente im DOM | **0** | **32** |
| Partieauswahl | `ct-hidden` | sichtbar |
| PGN-Datei angefragt | **nein** (0 Anfragen) | ja (1 Anfrage) |
| Partiekopf | – | „Weiß, Anton – Schwarz, Berta 1-0" |
| Länge des erzeugten Inhalts | 16.948 Zeichen | 22.747 Zeichen |

Die Prüfdateien liegen im Theme-Projekt und bleiben dort:

```text
F:\Claude\BSV-Redesign\contao-theme-layout6\pruefung\pgn-kopf.html    (ohne defer)
F:\Claude\BSV-Redesign\contao-theme-layout6\pruefung\pgn-defer.html   (mit defer)
F:\Claude\BSV-Redesign\contao-theme-layout6\pruefung\probe.pgn        (zwei Partien)
```

Sie binden die Dateien unmittelbar aus
`src/Resources/public/` dieses Repositories ein.

Passend dazu die Meldung in der Browserkonsole der laufenden Seite:

```text
document body not defined when setting up snackbar
Uncaught (in promise) TypeError: Cannot read properties of undefined (reading 'call')
    pgnviewerext.bundle.vers1.js
```

Die erste Meldung sagt es direkt: Das Skript läuft, wenn `document.body` noch
nicht existiert.

---

## Was ausgeschlossen wurde

Damit niemand dieselben Wege noch einmal geht – all das ist **in Ordnung**:

* **Die PGN-Datei** ist erreichbar: HTTP 200, 9.140 Zeichen, gültiges PGN mit
  mehreren Partien.
* **Das Figuren-CSS** wird geladen und ist aktiv. Nachgeprüft im Dokument:
  `.ct-piece-style-merida-gradient .ct-piece-blackbishop` mit einer
  `url("data:image/svg+xml…")` als Hintergrund.
* **Die Chunk-Pfade** stimmen. `window._ctChunkPath` steht auf
  `…/bundles/contaopgnviewer/pgnviewer/chunks/`; sowohl
  `chunks/piece-style-merida-pieces-css.bundle.js` als auch `chunks/16.css`
  antworten mit 200. (Ein 404 auf `pgnviewer/piece-style-…` ohne `chunks/`
  stammte aus einer Testanfrage auf einen geratenen Pfad, nicht aus dem
  Viewer.)
* **Das Custom Element** ist registriert: `customElements.get('ct-pgn-viewer')`
  liefert eine Klasse.
* **Das Theme** ist unbeteiligt: Der Fehler tritt in `pgn-kopf.html` ohne jedes
  Theme-Stylesheet genauso auf.

---

## Die Ursache

`addAssets()` meldet die Dateien ohne Flag an:

```php
$GLOBALS['TL_JAVASCRIPT']['contaopgnviewer_setup']  = $strBase . 'js/ct-setup.js';
$GLOBALS['TL_JAVASCRIPT']['contaopgnviewer_locale'] = $strBase . 'js/locale/' . … . '.js';
$GLOBALS['TL_JAVASCRIPT']['contaopgnviewer']        = $strBase . 'pgnviewer/pgnviewerext.bundle.vers1.js';
```

Contao gibt alles aus `TL_JAVASCRIPT` im Platzhalter `[[TL_HEAD_…]]` aus, und
der steht im `<head>` (`Contao\Controller`, Zusammenbau der Skript-Tags;
`PageRegular::createHeaderScripts()`). Ohne Flag entsteht also ein
`<script src="…">` **ohne** `defer` im Kopf der Seite.

Der Viewer sucht beim Start die vorhandenen `<ct-pgn-viewer>`-Elemente. Zu
diesem Zeitpunkt gibt es sie noch nicht – der Body ist nicht einmal angelegt.
Später eingefügte Elemente bedient er nicht mehr; sie bleiben als leere Hülle
stehen.

Dass es überhaupt nach einem Viewer aussieht, täuscht: Der sichtbare Rahmen
kommt aus dem serverseitig erzeugten Markup, nicht vom laufenden Viewer.

---

## Vorschlag zur Behebung

Contao kennt für `TL_JAVASCRIPT` das Flag `|defer` (siehe
`StringUtil::resolveFlaggedUrl()`; ausgewertet in `Contao\Controller` über
`$options->defer`). Ein Anhängen an alle drei Pfade genügte im Test:

```php
$GLOBALS['TL_JAVASCRIPT']['contaopgnviewer_setup']  = $strBase . 'js/ct-setup.js|defer';
$GLOBALS['TL_JAVASCRIPT']['contaopgnviewer_locale'] = $strBase . 'js/locale/' . … . '.js|defer';
$GLOBALS['TL_JAVASCRIPT']['contaopgnviewer']        = $strBase . 'pgnviewer/pgnviewerext.bundle.vers1.js|defer';
```

Die Reihenfolge bleibt dabei gewahrt: Mehrere `defer`-Skripte führt der
Browser in der Reihenfolge ihres Auftretens im Dokument aus. `ct-setup.js`
läuft also weiterhin vor `pgnviewerext.bundle.vers1.js`, was der Kommentar in
`addAssets()` ausdrücklich verlangt.

**Vor der Umsetzung zu prüfen:**

* Ob `|defer` mit `|static` zusammenwirkt, falls die Dateien später einmal
  über den Combiner laufen sollen.
* Ob das Backend-Vorschaufenster (`isBackend()`-Zweig) dieselbe Behandlung
  braucht.
* Ob es Seiten gibt, die den Viewer per JavaScript nachladen; dort ändert
  `defer` nichts, dann wäre zusätzlich ein Aufruf zum Nachregistrieren nötig.

Nach der Änderung: Changelog pflegen, Fassung ohne `v`-Präfix erhöhen, und die
beiden Prüfdateien oben noch einmal gegeneinander laufen lassen.

---

## Nachtrag der betreuenden Sitzung (2026-08-08)

### Nachgestellt

Mit der beiliegenden Turnierdatei (`files/thyrow.pgn`, sieben Partien) in der
Testinstallation Contao 5.7.7, PHP 8.3, drei Prüfseiten mit denselben Dateien:

| | Skripte im Kopf | Kopf mit `defer` | Ende des Body |
| --- | ---: | ---: | ---: |
| Figuren im DOM | **0** | 32 | 32 |
| Partieauswahl | verborgen | sichtbar, 7 Partien | sichtbar, 7 Partien |
| PGN-Datei angefragt | **nein** | ja | ja |
| Inhalt des Elements | 16.909 Zeichen | 37.401 Zeichen | 37.401 Zeichen |

Dazu in der Konsole genau die im Bericht genannten beiden Meldungen. Der Befund
ist damit vollständig bestätigt.

### Warum nicht `|defer`

`|defer` wirkt **nur in Contao 5**. In Contao 4.13 kennt
`StringUtil::resolveFlaggedUrl()` das Flag nicht (nur `static`, `async`, mtime
und media) und legt es im `default`-Zweig als Media-Angabe ab; auch
`Template::generateScriptTag()` hat dort keinen Parameter dafür. Das Bundle
unterstützt beide Fassungen, in 4.13 wäre der Fehler also geblieben.

`$GLOBALS['TL_BODY']` gibt es dagegen in beiden Fassungen, und der Kern selbst
baut damit seine Skript-Tags:
`Template::generateScriptTag(static::addAssetsUrlTo($pfad), false, null)`
(`PageRegular::createFooterScripts()`). Genau dieser Aufruf wird jetzt auch hier
verwendet — mitsamt Fassungsnummer aus dem Änderungsdatum, damit Browser eine
neue Fassung wirklich holen.

### Die drei Punkte „vor der Umsetzung zu prüfen“

* **`|defer` zusammen mit `|static`:** entfällt, das Bundle nutzt `TL_JAVASCRIPT`
  nicht mehr.
* **Backend-Vorschau:** nicht betroffen. Im Backend liefert `generate()` einen
  Platzhalter und kehrt zurück, bevor `compile()` und damit `addAssets()`
  überhaupt aufgerufen werden.
* **Später per JavaScript eingefügte Elemente:** brauchen nichts weiter.
  Gegenprobe im Browser — ein nach dem Laden erzeugtes `<ct-pgn-viewer>` wurde
  vollständig aufgebaut (32 Figuren, sieben Partien). Es ist ein echtes Custom
  Element, der Browser holt das von sich aus nach.

### Geprüft nach der Änderung

Contao 5.7.7 **und** Contao 4.13.58, je mit einer Seite im Aufbau einer echten
Contao-Seite (Stylesheets im Kopf, Skripte aus `TL_BODY` am Ende): 32 Figuren,
sieben Partien in der Auswahl, Kopfzeile und Zugliste gefüllt, deutsche Notation
(`Sxc3`, `Lc4`), keine Aufrufe fremder Server, keine fehlgeschlagenen Anfragen.
