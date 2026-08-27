/*
 * Ordnet die Bedienelemente des Chesstempo-Betrachters neu an.
 *
 * Der Betrachter baut Partieauswahl, Bedienknöpfe, Kopfzeile und Zugliste in
 * einen gemeinsamen Block neben dem Brett. Erwünscht ist aber, dass die
 * Auswahlliste unmittelbar über dem Brett steht und die Knöpfe darunter —
 * beide in der Breite des Brettes. Mit Stylesheets allein geht das nicht: Die
 * Elemente hängen im falschen Zweig des Dokuments, und CSS kann sie nicht
 * umhängen. Deshalb werden sie hier verschoben, sobald der Betrachter fertig
 * aufgebaut hat.
 *
 * Das Verschieben ist unbedenklich: Der Betrachter sucht diese Elemente später
 * über querySelector innerhalb seines eigenen Elements, und dort bleiben sie.
 *
 * Nebenbei bekommen die Bedienelemente ein title-Attribut. Der Betrachter
 * beschriftet sie nur für Vorleseprogramme (aria-label); mit title erscheint der
 * Zweck auch als Kurzhinweis unter dem Mauszeiger. Das betrifft nicht nur die
 * vier Knöpfe unter dem Brett, sondern auch die Einträge des Klappmenüs — die
 * sind als span ausgeführt und tragen nichts als ein Symbol.
 */
(function () {
	'use strict';

	/**
	 * Hängt Auswahlliste und Knopfleiste an das Brett und ergänzt die Titel.
	 *
	 * @param {Element} viewer Das Element ct-pgn-viewer
	 *
	 * @return {boolean} true, sobald der Betrachter aufgebaut war und die
	 *                   Umstellung erfolgen konnte
	 */
	function anordnen(viewer) {
		var halter = viewer.querySelector('.ct-pgn-viewer-board-holder');

		if (!halter) {
			return false;
		}

		var auswahl = viewer.querySelector('.ct-pgn-viewer-multi-game-selector-panel');
		var knopfleiste = viewer.querySelector('board-controls');

		/* Die Auswahlliste über das Brett */
		if (auswahl && auswahl.parentElement !== halter) {
			halter.insertBefore(auswahl, halter.firstChild);
		}

		/* Die Knopfleiste unter das Brett */
		if (knopfleiste && knopfleiste.parentElement !== halter) {
			halter.appendChild(knopfleiste);
		}

		titelErgaenzen(viewer);

		return true;
	}

	/**
	 * Überträgt die Beschriftung der Bedienelemente in ein title-Attribut.
	 *
	 * Erfasst wird alles innerhalb der Knopfleiste, was ein aria-label trägt —
	 * die vier Knöpfe ebenso wie die Einträge des Klappmenüs, die nur ein Symbol
	 * zeigen. Elemente ohne Beschriftung bleiben unangetastet, ebenso solche,
	 * die schon einen Titel haben.
	 *
	 * @param {Element} viewer Das Element ct-pgn-viewer
	 */
	function titelErgaenzen(viewer) {
		var knoepfe = viewer.querySelectorAll('board-controls [aria-label]');

		for (var i = 0; i < knoepfe.length; i++) {
			var beschriftung = knoepfe[i].getAttribute('aria-label');

			if (beschriftung && !knoepfe[i].getAttribute('title')) {
				knoepfe[i].setAttribute('title', beschriftung);
			}
		}
	}

	/**
	 * Wartet auf den fertigen Aufbau eines Betrachters und ordnet ihn dann neu.
	 *
	 * Der Betrachter baut sein Inneres selbst auf, und zwar erst nachdem der
	 * Browser das eigene HTML-Element eingerichtet hat. Wann das geschieht, ist
	 * von außen nicht vorhersehbar, deshalb wird das Element beobachtet.
	 *
	 * Die Beobachtung endet nicht beim ersten Erfolg: Der Betrachter setzt sein
	 * Inneres in mehreren Schritten zusammen, und das Klappmenü kann später
	 * dazukommen als die Knopfleiste selbst. Ein zweiter Durchlauf schadet
	 * nicht, denn anordnen() erkennt, was schon an seinem Platz ist. Das
	 * Verschieben löst zwar selbst eine Änderung aus, der folgende Durchlauf
	 * bleibt aber wirkungslos, und die title-Attribute lösen gar nichts aus:
	 * beobachtet werden nur Änderungen am Baum, nicht an Attributen.
	 *
	 * @param {Element} viewer Das Element ct-pgn-viewer
	 */
	function beobachten(viewer) {
		anordnen(viewer);

		var beobachter = new MutationObserver(function () {
			anordnen(viewer);
		});

		beobachter.observe(viewer, { childList: true, subtree: true });

		/* Nach zehn Sekunden ist der Aufbau in jedem Fall vorbei; danach würde
		   die Beobachtung nur noch bei jedem Zug mitlaufen. */
		window.setTimeout(function () {
			beobachter.disconnect();
		}, 10000);
	}

	function starten() {
		var betrachter = document.querySelectorAll('.ce_pgnviewer ct-pgn-viewer');

		for (var i = 0; i < betrachter.length; i++) {
			beobachten(betrachter[i]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', starten);
	} else {
		starten();
	}
})();
