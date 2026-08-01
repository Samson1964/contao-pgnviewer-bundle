/*
 * Notationssprache für den Chesstempo-PGN-Viewer: Franzoesisch
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
			"": { domain: "messages", lang: "fr", plural_forms: "nplurals=2; plural=(n != 1);" },
			"K": ["R"],
			"Q": ["D"],
			"R": ["T"],
			"B": ["F"],
			"N": ["C"],
			"P": ["P"]
		}
	}
};
