/*
 * Notationssprache für den Chesstempo-PGN-Viewer: Deutsch
 *
 * Muss vor pgnviewerext.bundle.vers1.js geladen werden.
 *
 * Achtung: Diese Fassung des Viewers erwartet die Übersetzung an der
 * ersten Stelle des Arrays (["D"]), nicht wie sonst bei Gettext üblich
 * an der zweiten ([null, "D"]). Mit der üblichen Schreibweise bleibt die
 * Notation stillschweigend englisch.
 */
var ctJsonLocaleData = {
	domain: "messages",
	locale_data: {
		messages: {
			"": { domain: "messages", lang: "de", plural_forms: "nplurals=2; plural=(n != 1);" },
			"K": ["K"],
			"Q": ["D"],
			"R": ["T"],
			"B": ["L"],
			"N": ["S"],
			"P": ["B"],
			"Jump to start": ["Zum Anfang"],
			"Move back. Shift-click/Middle-click jump to start": ["Ein Zug zurück. Mit Umschalt- oder Mittelklick an den Anfang"],
			"Move forward. Shift-click/Middle-click jump to end": ["Ein Zug vor. Mit Umschalt- oder Mittelklick ans Ende"],
			"Jump to end": ["Zum Ende"],
			"Board actions menu": ["Menü"],
			"Open menu": ["Menü öffnen"],
			"Show PGN": ["PGN anzeigen"],
			"View in database": ["In der Datenbank ansehen"],
			"Annotate": ["Kommentieren"],
			"Toggle autoplay": ["Automatisch abspielen"],
			"Toggle folding": ["Varianten einklappen"],
			"Toggle variations": ["Varianten anzeigen"],
			"Toggle fullscreen": ["Vollbild"],
			"Board settings": ["Bretteinstellungen"],
			"Rotate board": ["Brett drehen"],
			"Confirm": ["Bestätigen"],
			"Cancel": ["Abbrechen"],
			"Enter move , e.g. e2e4, e1g1 to castle, e7e8q to promote": ["Zug eingeben, z. B. e2e4, e1g1 für die Rochade, e7e8q für die Umwandlung"]
		}
	}
};
