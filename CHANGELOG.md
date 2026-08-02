# PGN-Betrachter Changelog

## Version 2.0.2 (2026-08-02)

* Add: Die Verträglichkeit mit **PHP 8.3** und **Contao 4.13.58 sowie 5.7.7** wurde nachgemessen. Dazu wurde in beiden Installationen im selben Durchlauf erst ein fremdes, dann das eigene Inhaltselement erzeugt und die Meldungen verglichen: Das Bundle steuert **keine einzige** Meldung bei, weder Warnungen noch Veraltetes. Die Meldungen, die dabei ohnehin anfallen (7 unter Contao 5, 13 unter Contao 4.13), stammen aus dem Kern und den Symfony-Komponenten.
* Add: Konfiguration für PHPStan (`phpstan.neon.dist`). Der Code ist auf **Stufe 8 ohne Befund**; wie er zu prüfen ist, steht in der `README.md`.
* Change: Die Felder aus der DCA sind jetzt als `@property` dokumentiert. Sie sehen wie Eigenschaften aus, laufen aber über `__get`/`__set` des Kerns — dadurch kannten weder Entwicklungsumgebung noch Analysewerkzeug ihre Namen und Typen.
* Change: Die Variablen des Templates werden in einem Zug übergeben statt einzeln zugewiesen. Das ist die dokumentierte Schnittstelle (`setData`), und der Downloadbereich liefert seine Angaben jetzt als Rückgabewert, statt das Template von innen zu verändern.
* Change: Die Erkennung der Backend-Anfrage prüft die beiden Dienste aus dem Container jetzt auf ihren Typ, statt sich auf die Benennung zu verlassen. Nebenbei entfiel eine Abfrage auf `null`, die nie zutreffen konnte.
* Change: `compile()` hat eine Rückgabeangabe, der Dateipfad wird ausdrücklich als Zeichenkette übergeben, und `strpos(…) !== false` ist dem lesbareren `str_contains` gewichen.
* Change: `symfony/http-foundation` steht jetzt in der `composer.json`, weil das Bundle die Klasse `RequestStack` unmittelbar verwendet.

## Version 2.0.1 (2026-08-02)

* Fix: Auf Mobilgeräten schob sich die ganze Seite seitwärts, sobald „Zugliste unter dem Brett“ gesetzt war. Das Chesstempo-Stylesheet gibt dem Brettbereich und dem Block aus Zugliste und Bedienknöpfen je 400 Pixel feste Breite, die in dieser Anordnung nicht schrumpfen konnten; bei 375 Pixel Fensterbreite ragte die Seite 41 Pixel über den Rand hinaus. Die Beschränkung gilt jetzt nur noch, soweit der Platz reicht — und ausdrücklich nur für diese Anordnung, denn bei der Zugliste rechts sorgt genau diese Mindestbreite für den Umbruch auf schmalen Bildschirmen.
* Fix: Das Brett verkleinert sich jetzt, wenn der Platz nicht reicht. Die eingestellte Figurengröße ergibt eine feste Brettbreite — bei 80 Pixel Figuren sind das 640 Pixel Brett, das auf kein Telefon passte. Es bleibt dabei quadratisch und die Figuren sitzen weiterhin genau auf den Feldern; ist genug Platz, ändert sich nichts.
* Fix: Die im Backend eingetragene Höhe der Zugliste erzeugte eine zweite Bildlaufleiste und schnitt die Liste ab, statt sie zu begrenzen. Der Wert wirkt jetzt auf den Bereich, in dem der Betrachter ohnehin schon scrollt.
* Fix: Der Betrachter schaltet den Fokusrahmen seiner Bedienknöpfe ab — wer mit der Tabulatortaste bediente, sah nicht, wo er stand. Knöpfe, Auswahlfeld und Zugeingabe bekommen wieder einen sichtbaren Rahmen, der sich hell oder dunkel an seinen Untergrund anpasst.

## Version 2.0.0 (2026-08-01)

Diese Fassung bringt zwei große Änderungen: den aktuellen PGN-Betrachter von Chesstempo und die Unterstützung von Contao 5. Bestehende Inhaltselemente laufen weiter; was sich für Redakteure ändert, steht im Abschnitt „Umstieg von Version 1“ der `README.md`.

**Neue Anzeige-Engine**

* Add: Der PGN-Viewer 2.5 von Chesstempo ersetzt die bisherige Fassung, die noch auf der 2010er YUI-Bibliothek beruhte. Neu sind unter anderem Varianten und Kommentare in der Zugliste, Vollbild, das Verschieben der Figuren mit der Maus, die Bedienung über die Pfeiltasten, eine eingebaute Partieauswahl und eine eingebaute Kopfzeile mit den Partiedaten.
* Add: Zehn weitere Figurensätze (Alpha, Wikipedia, Keltisch, Fantasy, Good Companion mit und ohne Verlauf, Kosal, Spatial, Eyes, Skulls, Merida mit Verlauf).
* Add: Figurengrößen bis 80 Pixel (Brett 640 Pixel); bisher war bei 46 Schluss.
* Change: **Der Betrachter läuft vollständig aus dem Bundle.** Ausgeliefert wird er von Chesstempo so, dass er Figurensätze, Schriften, Brett-Hintergründe und Zug-Geräusche zur Laufzeit von deren Servern nachlädt. Alle diese Dateien liegen jetzt im Bundle (15 Figurensätze, 34 Hintergrundbilder, zwei Schriften, vier Töne), und die vier Stellen mit fest verdrahteten Adressen sind auf das Bundle umgebogen — nachzulesen im Abschnitt „Änderungen an den Dateien von Chesstempo“ der `README.md`. Beim Aufruf einer Seite wird kein fremder Server mehr kontaktiert; im Browser nachgemessen.
* Add: Neues Feld **Brettstil** mit 31 Auswahlmöglichkeiten — dreizehn einfarbige und achtzehn mit Bild (Holz, Marmor, Granit, Stein, Putz, Metall, Leder, Stoff, Gras und weitere).
* Change: Die Notationssprachen (Deutsch, Französisch, Niederländisch, Polnisch, Spanisch, Tschechisch, Symbole hell und dunkel) sind auf das Format der neuen Engine umgestellt. Bei Deutsch sind zusätzlich die Beschriftungen der Bedienknöpfe übersetzt.
* Change: Die Einzelauswahl der Partiedaten entfällt, weil die neue Engine die Kopfzeile selbst zusammenstellt; aus den zwölf Kästchen ist ein einziges geworden. Der Figurensatz „Condal“ entfällt ersatzlos, betroffene Elemente fallen auf Merida zurück. „Autoscroll“ entfällt, die Zugliste scrollt von sich aus mit.
* Change: Die alte Engine samt YUI-Bibliothek, Figuren-Grafiken und Brett-Hintergründen ist entfallen. Das Paket schrumpft von 535 auf 49 Dateien.

**Contao 5**

* Add: Kompatibilität mit Contao 5 hergestellt, Contao 4.13 bleibt unterstützt. Beide Fassungen wurden in einer 4.13- und einer 5.7-Installation geprüft: DCA, Backend-Widgets, Einstellungen und die Ausgabe im Frontend laufen ohne Meldungen, und im Browser lädt die Seite alle Dateien aus dem Bundle.
* Change: Mindestanforderung ist PHP 8.1; die `composer.json` verlangt `contao/core-bundle ^4.13 || ^5.0` und die passenden Symfony-Komponenten.
* Change: Der gesamte PHP-Code nutzt `declare(strict_types=1)`, die Contao-Klassen werden über ihren Namensraum eingebunden (`Contao\ContentElement` statt `\ContentElement`) und die veralteten Funktionen `trimsplit`, `specialchars` sowie die direkten Zugriffe auf `$GLOBALS['TL_CONFIG']` sind durch `StringUtil` und `Config` ersetzt.
* Change: Im Backend zeigt das Element jetzt einen Platzhalter statt zu versuchen, das Brett aufzubauen. Die Backend-Erkennung läuft über den Scope-Matcher, weil es die Konstante `TL_MODE` in Contao 5 nicht mehr gibt.
* Change: Das Inhaltselement steht jetzt in der Gruppe „Schach-Elemente“ zusammen mit den übrigen Schach-Erweiterungen. Bisher lag es in einer Gruppe „chess“, für die es gar keine Beschriftung gab.
* Change: Aus `services.yml` wurde `services.yaml`, der Block für das in Symfony 7 entfernte `ContainerAwareInterface` ist weggefallen — er hätte Contao 5 blockiert.
* Add: Das Inhaltselement lässt sich jetzt über ein eigenes Template ausgeben (Feld „Template“ in der Palette).
* Add: Ausführliche Dokumentation in der `README.md` — Vorbereitung der PGN-Dateien, alle Felder des Inhaltselements, die globalen Einstellungen, Bedienung im Frontend, eigenes Aussehen, Fehlersuche, Umstiegshinweise und die eine Änderung an den Dateien von Chesstempo.
* Fix: Der Downloadlink hängt den Dateinamen nicht mehr über die in Contao 4 abgeschaffte Einstellung `disableAlias` an.
* Fix: Der Pfad zur PGN-Datei gilt jetzt vom Wurzelverzeichnis aus. Bisher wurde er relativ angegeben und wäre auf Unterseiten ins Leere gelaufen.

## Version 1.0.2 (2026-07-29)

* Change: Beschreibung, Keywords und Homepage in der composer.json ergänzt, damit Packagist das Paket verständlich darstellt und über die Suche auffindbar macht

## Version 1.0.1 (2025-02-07)

* Fix: Warning: Undefined array key "disableAlias" in ContentElements/PGNViewer.php (line 227) -> Config-Variable in config.php ergänzt

## Version 1.0.0 (2024-05-29)

* Fix: Warning: Undefined global variable $TL_CSS in config/config.php (line 43) 
* Fix: Warning: Undefined array key "pgnviewer_sound" in dca/tl_content.php (line 146) 
* Fix: Attempted to load class "ContentElement" from namespace "Schachbulle\ContaoPgnviewerBundle\ContentElements" in ContentElements/PGNViewer.php (line 40) 
* Fix: Attempted to load class "File" from namespace "Schachbulle\ContaoPgnviewerBundle\ContentElements" in ContentElements/PGNViewer.php (line 100) 
* Fix: Warning: Undefined array key "pgnviewer_notationlang" in ContentElements/PGNViewer.php (line 234) 
* Fix: syntax error, unexpected variable "$GLOBALS", expecting "function" or "const" in ContentElements/PGNViewer.php (line 263) 
* Change: Standard-CSS board-min.css -> Pfade zu den Hintergründen angepaßt
* Change: pgnviewer.js -> Pfade zu den Grafiken
* Change: Neue Grafiken für die Buttons
* Fix: Sound-Pfade

## Version 0.0.2 (2024-05-25)

* Add: Kompatibilität PHP 8
* Add: Dateien der neuen PGN-Engine von Chesstempo

## Version 0.0.1 (2021-08-05)

* Initiale Version aufbauend auf dem PGNViewer von Wilfried Krebbers