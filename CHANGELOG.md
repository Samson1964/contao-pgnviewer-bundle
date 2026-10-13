# PGN-Betrachter Changelog

## Version 2.3.2 (2026-10-13)

* Fix: **In der Anordnung „Zugliste umfließt das Brett“ klaffte vor einer Variante eine große Lücke.** Eine Variante ist im Stylesheet von Chesstempo ein Block mit `clear: both`; beim umflossenen Brett schob das die Variante unter das Brett und ließ den Platz davor leer. Gemeldet an [ddrpmm1959](https://www.schachbund.de/ddrpmm1959.html), wo nach 3. Sc3 gemessene 234 Pixel frei blieben; im Prüfstand mit einem 368 Pixel breiten Brett 351 Pixel. Die Variante bleibt ein eigener, eingerückter Block und beginnt jetzt gleich nach dem Zug. Die Zugliste der gemeldeten Partie wird dadurch von 967 auf 788 Pixel kürzer. Die beiden anderen Anordnungen sind nicht betroffen und bleiben unverändert — dort gibt es kein umflossenes Brett.
* Change: **Zweispaltige Zugliste und umfließendes Brett schließen sich aus.** In der zweispaltigen Darstellung beginnt jeder Zug von Weiß eine neue Zeile — genau das baut die beiden Spalten auf und lässt sich nicht abschalten. Ein umflossenes Brett wurde davon abgeräumt: Die Liste begann erst darunter (gemessen: erster Zug bei 551 Pixel, während der Brettblock bei 535 endet), und das Umfließen entfiel stillschweigend. Bei dieser Kombination gilt jetzt von vornherein „Zugliste unter dem Brett“.
* Add: Abschnitt **Schach960** in der `README.md`. Der Betrachter von Chesstempo kennt die Variante nicht — das Merkmal `[Variant "Chess960"]` wird nicht gelesen, die Rochade ist fest auf e1c1/e8c8 verdrahtet, und eine neuere Fassung des Betrachters gibt es nicht. Nachgemessen an vier Prüfpartien: Eine 960-Stellung erscheint richtig und lässt sich nachspielen, solange die Partie keine Rochade enthält und der FEN die Rochaderechte klassisch als `KQkq` angibt; mit einer Rochade oder mit Shredder-Rechten wie `HAha` bleibt der Betrachter leer.

## Version 2.3.1 (2026-08-27)

* Fix: **Jeder Klick auf „Ein Zug vor“ oder „Ein Zug zurück“ führte zwei Halbzüge aus** und übersprang damit immer den Zug des Gegners. Ursache war eine Regression aus Fassung 2.3.0: Die Knopfleiste ist ein eigenes HTML-Element, und als sie unter das Brett verschoben wurde, meldete der Betrachter seine Ereignisse ein zweites Mal an — sein `disconnectedCallback` ist leer und räumt nichts weg. Die Anmeldungen werden jetzt vor dem Verschieben abgemeldet. Nachgewiesen mit zwei Prüfständen, die sich nur darin unterscheiden, ob `js/ct-layout.js` geladen ist: ohne die Datei ein Halbzug je Klick, mit ihr zwei; jedes weitere Verschieben erhöhte um einen weiteren.
* Fix: **Das Ergebnis der Partie stand weit unter dem letzten Zug**, in der Anordnung „Zugliste umfließt das Brett“ sogar unterhalb des Brettes — bei einer kurzen Partie gemessene 370 Pixel dahinter. Der Betrachter setzt es als eigenen Block mit `clear: both` ans Ende der Zugliste. Es steht jetzt im Textfluss unmittelbar hinter dem letzten Zug.
* Fix: **Der Knopf „Zum Anfang“ blieb auf dem ersten Zug stehen**, statt davor: Das Brett zeigte bereits den Eröffnungszug von Weiß. Der Betrachter ruft dort nur `jumpToStart()` auf; ein `backMove()` fehlt.
* Change: **Eine Partie beginnt jetzt vor ihrem ersten Zug** — beim Laden der Seite wie beim Wechsel der Partie in der Auswahlliste. Bisher stand das Brett schon nach dem Zug von Weiß, ohne dass sich dieser Zug noch einmal betrachten ließ. Dafür sorgt das Attribut `dont-jump-start` des Betrachters, dessen Name in die Irre führt: Er springt sehr wohl an den Anfang, nur eben einen Halbzug weiter zurück.

## Version 2.3.0 (2026-08-27)

* Add: **Voreinstellungen für alle Inhaltselemente.** Unter *Einstellungen → PGNViewer* lassen sich Figurensatz, Figurengröße, Brettstil, Koordinaten, Partiedaten, Anordnung, Höhe der Zugliste, Pause zwischen den Zügen, der Hinweis auf Chess Tempo und der Download der PGN-Datei einmal für die ganze Installation festlegen. Ein neues Inhaltselement startet mit diesen Werten; eigene Voreinstellungen über eine projekteigene `tl_content.php` erübrigen sich damit.
* Add: **Freigabe je Feld.** Das Kästchenfeld „Im Inhaltselement überschreibbar“ bestimmt, welche der zehn Angaben ein Redakteur im einzelnen Element überhaupt ändern darf. Ein nicht freigegebenes Feld verschwindet aus der Eingabemaske und richtet sich überall nach dem globalen Wert — es ließen sich sonst Werte eintragen, die nachher niemand beachtet. Sein gespeicherter Eintrag bleibt erhalten und gilt wieder, sobald das Feld erneut freigegeben wird. Solange in den Einstellungen nichts gespeichert wurde, sind alle zehn Felder frei; bestehende Websites verhalten sich nach der Aktualisierung also unverändert.
* Change: Ist der **Download der PGN-Datei** gesperrt und eingeschaltet, bleiben Link-Text und Link-Title im Inhaltselement stehen — die Beschriftung gehört zur einzelnen Datei und lässt sich nicht sinnvoll global vorgeben. Ist er gesperrt und ausgeschaltet, entfällt der Downloadbereich der Eingabemaske ganz.
* Change: **Die Partieauswahl steht jetzt unmittelbar über dem Brett** und ist genauso breit wie dieses. Bisher lief sie über die gesamte Breite des Elements und stand weit vom Brett entfernt.
* Change: **Die Bedienknöpfe stehen jetzt unter dem Brett**, in allen drei Anordnungen. In der Anordnung „Zugliste umfließt das Brett“ lagen sie bisher über der Zugliste.
* Fix: **Auf schmalen Bildschirmen standen die Bedienknöpfe untereinander** statt nebeneinander und beanspruchten dadurch die halbe Höhe des Bildschirms. Sie bleiben jetzt in einer Reihe, bis hinunter zu 320 Pixel Bildschirmbreite.
* Add: Jedes Bedienelement trägt einen Hinweistext, der beim Verweilen mit der Maus erscheint. Der Betrachter liefert die Beschriftungen nur für Screenreader mit (`aria-label`); sie werden jetzt zusätzlich als `title` gesetzt. Das betrifft die vier Knöpfe unter dem Brett ebenso wie die zehn Einträge des Klappmenüs, die nichts als ein Symbol zeigen — von „Brett drehen“ bis „Automatisch abspielen“.
* Change: Die Kopfzeile mit Turnier, Ort, Runde, ECO-Schlüssel und Jahr erscheint etwas kleiner und kursiv, damit sie sich von den Namen der Spieler abhebt.

## Version 2.2.0 (2026-08-09)

* Fix: **In der Anordnung „Zugliste umfließt das Brett“ war das Brett unsichtbar.** Der Block mit der Zugliste ist positioniert und hat eine eigene Fläche; dadurch legte er sich über das umflossene Brett und verdeckte es vollständig. Sichtbar blieb es nur die ersten Augenblicke nach dem Laden, solange die Zugliste noch leer war — daher der Eindruck, es erscheine beim Neuladen kurz. Das Brett liegt jetzt oben auf.
* Change: **Der Betrachter übernimmt Schrift- und Hintergrundfarbe der Website.** Sein mitgeliefertes Design ist dunkel — fast schwarze Flächen, helle Schrift —, was auf einer hellen Seite wie ein Fremdkörper wirkte und die Zugliste als schwarzen Kasten stehen ließ. Sein Stylesheet ist durchgehend über Variablen des Material-Designs aufgebaut; das Bundle setzt diese jetzt auf „durchsichtig“ und „geerbte Schriftfarbe“. Das wirkt in einem hellen wie in einem dunklen Theme, ohne dass etwas einzustellen wäre. Das Brett behält seine über den Brettstil gewählten Farben.
* Change: Kommentare, Varianten und das Ergebnis waren im Original farbig abgesetzt und erreichten auf hellem Grund nur ein Kontrastverhältnis von 2,5:1. Da keine Farbe auf hellem und dunklem Grund gleichermaßen trägt, setzen sie sich jetzt über eine geringere Deckkraft ab: gemessen 8,3:1 auf hellem und 9,6:1 auf dunklem Grund. Wer einen eigenen Akzent möchte, setzt `--pgn-akzent` im Stylesheet der Website.

## Version 2.1.0 (2026-08-08)

* Add: **Neue Anordnung „Zugliste umfließt das Brett“.** Das Brett steht rechts oben, die Zugliste beginnt links daneben und läuft unter dem Brett über die volle Breite weiter — wie Text um ein Bild. Für lange Partien braucht die Zugliste damit deutlich weniger Höhe. Vor allem aber **bleibt das Brett beim Scrollen stehen**: Bisher wanderte es beim Durchklicken einer langen Notation aus dem Bild, so dass man die Züge nicht mehr verfolgen konnte. Die Bedienknöpfe stehen in dieser Anordnung über der Zugliste, damit sie in der Nähe des Brettes bleiben. Auf Bildschirmen unter 600 Pixel Breite steht das Brett wieder über der Zugliste.
* Change: Aus dem Kästchen „Zugliste unter dem Brett“ ist ein Auswahlfeld **„Anordnung“** mit drei Möglichkeiten geworden. Bestehende Elemente behalten ihre Einstellung: Der bisherige Wert bedeutet unverändert „Zugliste unter dem Brett“.
* Fix: Ein Brett, das breiter ist als der Bildschirm, ragte in der Voreinstellung „Zugliste rechts“ über den Rand hinaus — bei Figurengröße 46 auf einem 375 Pixel breiten Telefon um neun Pixel. Die Verkleinerung aus Fassung 2.0.1 galt bisher nur für die Anordnung „Zugliste unter dem Brett“ und wirkt jetzt in allen dreien.

## Version 2.0.3 (2026-08-08)

* Fix: **Der Betrachter blieb auf manchen Seiten leer** — Rahmen und Knopfleiste erschienen, aber ohne Brett, Figuren und Partieauswahl. Die drei Skripte wurden über `TL_JAVASCRIPT` angemeldet, und das gibt Contao im Seitenkopf aus. Der Betrachter sucht beim Start die `<ct-pgn-viewer>`-Elemente im Dokument; im Seitenkopf läuft er, bevor es den Body überhaupt gibt, bricht mit „document body not defined“ ab und lässt eine leere Hülle stehen. Die Skripte kommen jetzt über `TL_BODY` ans Ende des Body. Gemeldet aus der Sitzung zum BSV-Theme, Einzelheiten in der `BUGREPORT.md`.
* Change: Die script-Tags baut jetzt der Kern (`Template::generateScriptTag`). Dadurch hängt an jeder Datei eine Fassungsnummer aus ihrem Änderungsdatum — nach einer Aktualisierung holen Browser die neue Fassung, statt die alte aus dem Zwischenspeicher zu nehmen.
* Add: Hinweis in der `README.md`, dass die Skripte ans Ende des Body gehören, samt Eintrag in der Fehlersuche für den Fall, dass ein eigenes Seitenlayout den Platzhalter `TL_BODY` nicht ausgibt.

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