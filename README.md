# PGN-Betrachter

Der PGN-Betrachter stellt für Contao das Inhaltselement **PGNViewer** bereit. Damit lassen sich
Schachpartien im PGN-Format auf der Website nachspielen: mit Brett, Zugliste, Bedienknöpfen,
Partiedaten und automatischem Ablauf. Als Anzeige-Engine dient der PGN-Viewer 2.5 von
[Chesstempo](https://chesstempo.com/).

Enthält eine PGN-Datei mehrere Partien, blendet der Betrachter über dem Brett eine Auswahlliste
ein, über die sich zwischen den Partien umschalten lässt.

* Contao 4.13 und Contao 5
* PHP 8.1 oder neuer
* **Keine Aufrufe fremder Server:** Alle Dateien des Betrachters liegen im Bundle. Beim Aufruf
  einer Seite wird nichts von chesstempo.com nachgeladen.

Geprüft wurde mit PHP 8.3 gegen Contao 4.13.58 und Contao 5.7.7.

## Inhalt

* [Installation](#installation)
* [Vorbereitung: PGN-Dateien hochladen](#vorbereitung-pgn-dateien-hochladen)
* [Das Inhaltselement anlegen](#das-inhaltselement-anlegen)
* [Die Felder im Einzelnen](#die-felder-im-einzelnen)
* [Globale Einstellungen](#globale-einstellungen)
* [Bedienung im Frontend](#bedienung-im-frontend)
* [Eigenes Aussehen](#eigenes-aussehen)
* [Wenn etwas nicht funktioniert](#wenn-etwas-nicht-funktioniert)
* [Umstieg von Version 1](#umstieg-von-version-1)
* [Änderungen an den Dateien von Chesstempo](#änderungen-an-den-dateien-von-chesstempo)
* [Entwickler](#entwickler)

## Installation

Über den Contao Manager nach `schachbulle/contao-pgnviewer-bundle` suchen und installieren, oder
auf der Kommandozeile:

```bash
composer require schachbulle/contao-pgnviewer-bundle
```

Danach die Datenbank aktualisieren (Contao Manager → Systemwartung → Datenbank aktualisieren oder
`vendor/bin/contao-console contao:migrate`).

## Vorbereitung: PGN-Dateien hochladen

PGN ist in Contao kein bekannter Dateityp. Damit sich solche Dateien überhaupt in die
Dateiverwaltung hochladen lassen, muss die Endung einmalig freigeschaltet werden:

1. **Einstellungen → Uploads → Erlaubte Dateitypen**: `pgn` in die Liste aufnehmen.
2. Soll die Datei später auch heruntergeladen werden können, zusätzlich unter
   **Einstellungen → Dateien → Erlaubte Download-Dateitypen** `pgn` ergänzen.

Danach die PGN-Datei wie jede andere Datei in die Dateiverwaltung hochladen, zum Beispiel nach
`files/partien/`.

Die Datei darf beliebig viele Partien enthalten. Ausgewertet werden die üblichen Tags:

```
[Event "Berliner Meisterschaft"]
[Site "Berlin GER"]
[Date "2024.05.29"]
[Round "1"]
[White "Krebbers, Wilfried"]
[Black "Hoppe, Frank"]
[Result "1-0"]
[WhiteElo "2100"]
[BlackElo "2050"]
[ECO "B01"]

1. e4 d5 2. exd5 Qxd5 3. Nc3 Qa5 4. d4 Nf6 5. Nf3 1-0
```

Wichtig ist die **Leerzeile zwischen Kopfbereich und Zugfolge** sowie eine Leerzeile zwischen zwei
Partien. Varianten in Klammern, Kommentare in geschweiften Klammern und NAG-Zeichen (`$1`, `!?`)
versteht der Betrachter ebenfalls.

> Der Betrachter lädt die PGN-Datei im Browser des Besuchers nach. Sie muss deshalb öffentlich
> erreichbar sein – Dateien in einem geschützten Bereich funktionieren nicht.

## Das Inhaltselement anlegen

Im Artikel ein neues Inhaltselement anlegen und unter der Gruppe **Schach-Elemente** den Typ
**PGNViewer** wählen. Pflichtangabe ist lediglich die PGN-Datei; alle übrigen Felder haben
brauchbare Voreinstellungen.

Mehrere PGNViewer-Elemente auf derselben Seite sind möglich; jedes Brett arbeitet unabhängig.

## Die Felder im Einzelnen

### Element-Konfiguration

| Feld | Bedeutung |
| --- | --- |
| **PGN-Datei** | Die anzuzeigende Datei aus der Dateiverwaltung. Es werden nur Dateien mit der Endung `.pgn` zur Auswahl angeboten. |
| **Figurensatz** | Merida (Voreinstellung), Merida mit Verlauf, Leipzig, Maya, Case, Kingdom, Alpha, Wikipedia, Keltisch, Fantasy, Good Companion (auch mit Verlauf), Kosal, Spatial, Eyes und Skulls. |
| **Figurengröße** | Kantenlänge einer Figur in Pixel, von 20 bis 80. Das Brett wird achtmal so breit; bei 46 Pixeln ist es also 368 Pixel breit. |
| **Brettstil** | Aussehen der Felder: dreizehn einfarbige Varianten (Blau, Grün, Braun, mehrere Grautöne, teils mit Verlauf) und achtzehn mit Bild (Holz in fünf Tönen, Marmor, Granit, rissiger Stein, Putz, Sand und Schnee, Metall, Gold und Silber, Leder, Stoff, Gras, Tarnmuster, grünes Plasma). Ohne Auswahl bleibt es bei der Voreinstellung des Betrachters (Blau mit Verlauf). |
| **Koordinaten anzeigen** | Blendet die Linien- und Reihenbezeichnungen am linken und unteren Brettrand ein. |
| **Partiedaten anzeigen** | Zeigt über der Zugliste die Kopfzeile mit Namen, Wertungszahlen, Ergebnis, Turnier, Ort, Runde, ECO-Schlüssel und Datum. |
| **Zugliste unter dem Brett** | Ohne Haken steht die Zugliste rechts neben dem Brett, mit Haken darunter. |
| **Zugliste zweispaltig** | Stellt die Züge in zwei Spalten dar. Ohne Haken erscheinen die Hauptzüge untereinander, Varianten und Kommentare eingerückt. |
| **Höhe der Zugliste** | Größte Höhe der Zugliste in Pixel; darüber hinaus wird gescrollt. `0` hebt die Beschränkung auf. |
| **Pause zwischen den Zügen** | Wartezeit in Millisekunden beim automatischen Abspielen. Voreinstellung 800. |
| **Ton aktivieren** | Begleitet Züge und Schlagzüge mit einem Geräusch. Das Feld ist nur auswählbar, wenn der Ton in den Einstellungen freigegeben wurde (siehe unten). |
| **Hinweis auf Chess Tempo anzeigen** | Blendet unter dem Brett den Hinweis „PGN Viewer powered by chesstempo.com“ ein. Der Betrachter steht unter einer Creative-Commons-Lizenz und darf auf nicht-kommerziellen Seiten nur mit diesem sichtbaren Link verwendet werden. |

### Download-Einstellungen

| Feld | Bedeutung |
| --- | --- |
| **Download der PGN-Datei** | Bietet die Datei unterhalb des Brettes zum Herunterladen an. Schaltet die beiden folgenden Felder frei. Der Link erscheint nur, wenn `pgn` in den Einstellungen als Download-Dateityp erlaubt ist. |
| **Link-Text** | Beschriftung des Links. Ohne Angabe wird der Dateiname verwendet. |
| **Link-Title** | Inhalt des `title`-Attributs. Ohne Angabe wird der Link-Text verwendet. |

### Weitere Bereiche

Template, Zugriffsschutz, CSS-ID/Klasse und die Sichtbarkeitssteuerung verhalten sich wie bei jedem
anderen Contao-Inhaltselement.

## Globale Einstellungen

Unter **Einstellungen → PGNViewer** gelten die folgenden Angaben für alle Elemente der Installation:

| Einstellung | Bedeutung |
| --- | --- |
| **Notationssprache** | Buchstaben der Figuren in der Zugliste: Englisch (Voreinstellung, KQRBNP), Deutsch (KDTLSB), Französisch, Niederländisch, Polnisch, Spanisch, Tschechisch sowie zwei Varianten mit Figurensymbolen (hell ♔♕♖♗♘♙ und dunkel ♚♛♜♝♞♟). Die Angabe betrifft nur die Anzeige – in der PGN-Datei stehen immer die englischen Buchstaben. Bei Deutsch sind zusätzlich die Beschriftungen der Bedienknöpfe übersetzt. |
| **„Ton aktivieren“ freischalten** | Solange dies aus ist, lässt sich der Ton in keinem Inhaltselement anwählen und es werden grundsätzlich keine Geräusche abgespielt. |

## Bedienung im Frontend

Unter dem Brett liegt eine Knopfleiste: an den Anfang, ein Zug zurück, ein Zug vor, ans Ende,
automatisch abspielen sowie ein Menü mit weiteren Befehlen (Brett drehen, Vollbild, Varianten
ein- und ausklappen, PGN anzeigen, Bretteinstellungen). Ein Klick auf einen Zug in der Liste
springt zu dieser Stellung, mit den Pfeiltasten lässt sich die Partie durchblättern, und Figuren
können mit der Maus verschoben werden, um eigene Varianten auszuprobieren.

Änderungen, die ein Besucher an den Bretteinstellungen vornimmt, merkt sich sein Browser
(localStorage) und gelten dann auch auf anderen Seiten Ihrer Website.

Der Menüpunkt **In der Datenbank ansehen** öffnet die Stellung bei chesstempo.com. Das geschieht
erst beim Klick und in einem neuen Fenster.

## Eigenes Aussehen

Für kleinere Anpassungen genügt eigenes CSS im Seitenlayout. Das Bundle bringt zwei Stylesheets
mit: `pgnviewer/pgnviewerext.vers1.css` von Chesstempo mit dem gesamten Aussehen des Betrachters
und `css/pgnviewer.css` mit den Ergänzungen des Bundles.

Die Stylesheets stehen im Seitenkopf, die drei Skripte dagegen **am Ende des Body**. Das muss so
sein: Der Betrachter sucht beim Start die `<ct-pgn-viewer>`-Elemente im Dokument und bricht ab,
wenn es sie noch nicht gibt. Wer ein eigenes Seitenlayout baut, sollte den Platzhalter für
`TL_BODY` also nicht aus dem Template entfernen.

Die wichtigsten Klassen: `.ct-pgn-viewer` (das ganze Element), `.ct-pgn-viewer-board` (Brett),
`.ct-board-move-mainline` und `.ct-board-move-current` (Züge in der Liste), `.ct-nav-buttons`
(Knopfleiste), `.pgn_backlink` und `.pgn_download` (die beiden Zeilen des Bundles).

Reicht das nicht, lässt sich unter **Template** ein eigenes Template auswählen. Dazu die Datei
`vendor/schachbulle/contao-pgnviewer-bundle/src/Resources/contao/templates/ce_pgnviewer.html5` nach
`templates/` kopieren, umbenennen (zum Beispiel `ce_pgnviewer_kompakt.html5`) und dort anpassen.
Im Template steht das Element `<ct-pgn-viewer>`; alle Einstellungen werden ihm als Attribute
übergeben. Der Betrachter kennt noch weitere Attribute, die das Bundle nicht anbietet, etwa
`flip="true"` (Brett gedreht), `color-on-bottom="black"` (Schwarz unten) oder
`move-list-useFigurineNotation="true"` (Figurensymbole statt Buchstaben in der Zugliste).

## Wenn etwas nicht funktioniert

| Beobachtung | Ursache |
| --- | --- |
| Die PGN-Datei lässt sich nicht hochladen | `pgn` fehlt unter **Einstellungen → Uploads → Erlaubte Dateitypen**. |
| Der Downloadlink erscheint nicht, obwohl er eingeschaltet ist | `pgn` fehlt unter **Einstellungen → Dateien → Erlaubte Download-Dateitypen**. |
| Das Feld **Ton aktivieren** ist ausgegraut | Der Ton ist unter **Einstellungen → PGNViewer** nicht freigegeben. |
| Brett und Zugliste bleiben leer | Der Betrachter konnte die PGN-Datei nicht laden oder nicht lesen. Häufigste Ursachen: die Datei liegt in einem geschützten Verzeichnis, oder es fehlt die Leerzeile zwischen Kopfbereich und Zugfolge. |
| Die Auswahlliste der Partien fehlt | Sie erscheint erst, wenn die Datei mehr als eine Partie enthält. |
| Die Figuren fehlen, das Brett ist leer | Die Dateien des Figurensatzes wurden nicht mit veröffentlicht. Im Contao Manager „Assets neu installieren“ ausführen bzw. `vendor/bin/contao-console contao:setup`. |
| Nur der Rahmen erscheint: kein Brett, keine Figuren, keine Partieauswahl | Die Skripte des Betrachters laufen zu früh. Das eigene Seitenlayout gibt vermutlich den Platzhalter für `TL_BODY` nicht aus, so dass sie nicht am Ende des Body landen. In der Browserkonsole steht dann „document body not defined“. |
| Der Ton bleibt stumm | Browser spielen Töne erst ab, nachdem der Besucher etwas auf der Seite angeklickt hat. |

## Umstieg von Version 1

Version 2 tauscht die Anzeige-Engine aus: statt des alten, auf der YUI-Bibliothek von 2010
beruhenden Betrachters kommt der aktuelle von Chesstempo zum Einsatz. Bestehende Inhaltselemente
funktionieren weiter, es ändert sich aber einiges:

* **Die Partiedaten lassen sich nicht mehr einzeln auswählen.** Der neue Betrachter stellt die
  Kopfzeile selbst zusammen; aus den zwölf Kästchen ist ein einziges geworden. Wer vorher
  irgendetwas ausgewählt hatte, bekommt jetzt die vollständige Kopfzeile.
* **Der Figurensatz „Condal“ entfällt**, den gibt es im neuen Betrachter nicht mehr. Betroffene
  Elemente fallen auf Merida zurück; dafür kommen zehn neue Sätze hinzu.
* **Autoscroll entfällt** als Einstellung – die Zugliste scrollt von sich aus mit.
* **„Züge formatiert ausgeben“ heißt jetzt „Zugliste zweispaltig“** und schaltet zwischen der
  eingerückten und der zweispaltigen Darstellung um.
* **„Brett vor Notation ausgeben“ heißt jetzt „Zugliste unter dem Brett“.** Ohne Haken steht die
  Zugliste rechts neben dem Brett statt darüber.
* Die Brettfarbe ist neu einstellbar, ebenso größere Figuren (bis 80 Pixel).

Nach dem Aktualisieren einmal die Datenbank aktualisieren und die Assets neu installieren.

## Änderungen an den Dateien von Chesstempo

Der Betrachter wird so ausgeliefert, dass er Figurensätze, Schriften, Brett-Hintergründe und
Zug-Geräusche zur Laufzeit von den Chesstempo-Servern nachlädt — die Adressen stehen fest im
Programm und im Stylesheet. Damit wirklich alles aus dem Bundle kommt, sind an vier Stellen die
Pfade geändert; sonst ist nichts angerührt.

In `pgnviewer/pgnviewerext.bundle.vers1.js`:

```js
// Verzeichnis der Figurensätze
i.p="https://c1a.chesstempo.com/pgnviewer/v1/"
i.p=window._ctChunkPath||"/bundles/contaopgnviewer/pgnviewer/chunks/"

// Voreinstellung für Töne und Bilder
window._ctSoundPath="https://c2a.chesstempo.com",window._ctImgPath="https://c2a.chesstempo.com"
window._ctSoundPath="/bundles/contaopgnviewer/pgnviewer",window._ctImgPath="/bundles/contaopgnviewer/pgnviewer"
```

In `pgnviewer/pgnviewerext.vers1.css`:

* die beiden `@font-face`-Regeln zeigen auf `chessalphanew-webfont.woff` und
  `MaterialIcons-Regular.woff2` im selben Verzeichnis statt auf `c1a.chesstempo.com/fonts/`
  (die Formate `eot`, `ttf` und `svg` für alte Browser sind dabei entfallen),
* die 37 Verweise auf `/images/board-backgrounds/` sind relativ gesetzt und zeigen damit auf
  `pgnviewer/images/board-backgrounds/` im Bundle.

Die eigentlichen Pfade setzt `js/ct-setup.js` anhand der eigenen Adresse — dadurch stimmen sie auch,
wenn Contao in einem Unterverzeichnis läuft. Die Angaben im Programm sind nur der Rückfall, falls
diese Datei einmal fehlt; auf einen fremden Server zeigen sie nicht mehr.

**Nachprüfen lässt sich das im Browser:** Entwicklerwerkzeuge öffnen, Reiter „Netzwerk“, Seite neu
laden. Es darf keine Zeile mit `chesstempo.com` erscheinen.

Übrig bleiben zwei Verweise auf chesstempo.com, die nichts nachladen: der von der Lizenz verlangte
Hinweis unter dem Brett und der Menüpunkt **In der Datenbank ansehen**, der die angezeigte Partie
erst beim Klick an chesstempo.com schickt und dort in einem neuen Fenster öffnet. Wer diesen
Menüpunkt nicht möchte, blendet ihn mit einer Zeile eigenem CSS aus:

```css
.ct-board-action-viewInDb { display: none; }
```

Die Dateien stammen von `https://c1a.chesstempo.com/pgnviewer/v2.5/` (Programm),
`https://c2a.chesstempo.com/pgnviewer/v2.5/` (Stylesheet), `https://c1a.chesstempo.com/fonts/`
(Schriften), `https://c1a.chesstempo.com/pgnviewer/v1/` (Figurensätze, jetzt unter
`pgnviewer/chunks/`) und `https://c2a.chesstempo.com/images/board-backgrounds/` (Brett-Hintergründe).

## Für Entwickler: statische Analyse

Das Bundle bringt eine Konfiguration für [PHPStan](https://phpstan.org/) mit und ist auf Stufe 8
ohne Befund. Da die Klassen des Contao-Kerns nicht zum Bundle gehören, braucht der Aufruf den
Autoloader einer Contao-Installation:

```bash
phpstan analyse --autoload-file=/pfad/zur/contao-installation/vendor/autoload.php
```

Stufe 9 ist nicht sinnvoll: Contao legt seine Konfiguration in `$GLOBALS` ab, was für PHPStan ein
`mixed`-Wert ist — dort meldet jede Zeile einer DCA-Datei einen Befund, in jedem Contao-Bundle.

## Entwickler

Wilfried Krebbers entwickelte ab 2012 die erste Version dieser Erweiterung. Frank Hoppe hat sie
2024 auf Contao 4 und PHP 8 gebracht und pflegt sie seitdem weiter, da Wilfried die Entwicklung
eingestellt hat.

Die Erweiterung steht unter der LGPL-3.0-or-later, siehe [LICENSE](LICENSE). Der enthaltene
PGN-Viewer stammt von Chesstempo und steht unter einer Creative-Commons-Lizenz
(Attribution-Noncommercial-No Derivative Works); auf nicht-kommerziellen Seiten ist er nur mit
sichtbarem Link auf chesstempo.com erlaubt.
