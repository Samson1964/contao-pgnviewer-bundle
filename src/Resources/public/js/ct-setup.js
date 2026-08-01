/*
 * Vorbereitung für den Chesstempo-PGN-Viewer 2.5.
 *
 * Diese Datei muss vor pgnviewerext.bundle.vers1.js geladen werden.
 *
 * Der Viewer setzt beim Start die Pfade für Töne und Bilder fest auf die
 * Chesstempo-CDN – aber nur, wenn window._ctDev nicht gesetzt ist. Mit dem
 * Schalter bleiben die hier gesetzten Pfade stehen, und der Viewer holt alles
 * aus dem Bundle. So entsteht beim Aufruf einer Seite kein einziger Zugriff auf
 * einen fremden Server.
 *
 * Die Figurensätze liegen als einzelne CSS-Dateien vor, die der Viewer erst bei
 * Bedarf nachlädt. Woher, steht im Original fest verdrahtet im Bundle; die eine
 * Stelle wurde auf window._ctChunkPath umgestellt (siehe README, Abschnitt
 * „Änderungen an den Dateien von Chesstempo“), damit auch diese Dateien aus dem
 * Bundle kommen.
 *
 * Die Pfade werden aus der Adresse dieser Datei abgeleitet, damit sie auch dann
 * stimmen, wenn Contao in einem Unterverzeichnis läuft.
 */
(function () {
	'use strict';

	var script = document.currentScript;

	if (!script) {
		var all = document.getElementsByTagName('script');
		script = all[all.length - 1];
	}

	/* Von .../bundles/contaopgnviewer/js/ct-setup.js auf das Bundle-Verzeichnis zurück */
	var base = script.src.replace(/\/js\/ct-setup\.js.*$/, '');

	window._ctDev = true;
	window._ctLogLevel = 2;
	window._ctSoundPath = base + '/pgnviewer';
	window._ctImgPath = base + '/pgnviewer';
	window._ctChunkPath = base + '/pgnviewer/chunks/';
})();
