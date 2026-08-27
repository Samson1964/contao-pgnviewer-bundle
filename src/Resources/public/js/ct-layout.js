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
 *
 * Zwei Eigenheiten des Betrachters werden dabei geradegerückt: Er meldet seine
 * Ereignisse beim Umhängen ein zweites Mal an (siehe ereignisseAbmelden), und
 * sein Knopf „Zum Anfang“ bleibt auf dem ersten Zug stehen, statt davor
 * (siehe anfangKorrigieren).
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
			ereignisseAbmelden(knopfleiste);
			halter.appendChild(knopfleiste);
		}

		if (knopfleiste) {
			anfangKorrigieren(knopfleiste);
		}

		titelErgaenzen(viewer);

		return true;
	}

	/**
	 * Meldet die Ereignisse der Knopfleiste ab, bevor sie verschoben wird.
	 *
	 * Die Knopfleiste ist ein eigenes HTML-Element. Wird sie im Dokument
	 * umgehängt, ruft der Browser ihr connectedCallback erneut auf, und der
	 * Betrachter meldet seine Ereignisse noch einmal an — sein
	 * disconnectedCallback ist leer und räumt nichts weg. Jeder Klick liefe
	 * dann zweimal: „Ein Zug vor“ spränge über den gegnerischen Zug hinweg.
	 *
	 * Die Anmeldungen laufen über einen Vermittler unter „_del“, dessen
	 * detach() sie wieder entfernt. Sein destroy() ist in dieser Fassung des
	 * Betrachters eine leere Funktion und hilft nicht.
	 *
	 * Kennt eine spätere Fassung des Betrachters diesen Weg nicht mehr, bleibt
	 * es beim Verschieben ohne Abmelden — dann kehrt der doppelte Zug zurück,
	 * aber nichts bricht ab.
	 *
	 * @param {Element} knopfleiste Das Element board-controls
	 */
	function ereignisseAbmelden(knopfleiste) {
		var vermittler = knopfleiste._del;

		if (vermittler && typeof vermittler.detach === 'function') {
			vermittler.detach(knopfleiste);
		}
	}

	/**
	 * Lässt den Knopf „Zum Anfang“ vor den ersten Zug springen.
	 *
	 * Der Betrachter ruft dort nur jumpToStart() auf, und das führt auf den
	 * ersten Zug der Partie — das Brett zeigt also bereits den Zug von Weiß.
	 * Beim Laden und beim Wechsel der Partie macht der Betrachter es selbst
	 * richtig, sofern das Attribut dont-jump-start gesetzt ist: Dort hängt er
	 * ein backMove() an. Genau das wird hier für den Knopf nachgeholt.
	 *
	 * Die Knopfleiste wirft ihre Knöpfe bei jedem Umhängen weg und baut sie neu
	 * auf. Deshalb wird bei jedem Durchlauf geprüft, ob die Ersetzung noch am
	 * aktuellen Knopf hängt; das Merkmal am Knopf verhindert, dass sie ohne Not
	 * mehrfach gesetzt wird.
	 *
	 * @param {Element} knopfleiste Das Element board-controls
	 */
	function anfangKorrigieren(knopfleiste) {
		var knoepfe = knopfleiste._buttonsByName;

		if (!knoepfe || !knoepfe.jumpToStart || knoepfe.jumpToStart.ctAngepasst) {
			return;
		}

		knoepfe.jumpToStart.action = function () {
			var zugliste = this._board && this._board.moveList;

			if (!zugliste) {
				return;
			}

			zugliste.jumpToStart();

			if (typeof zugliste.atStart === 'function' && !zugliste.atStart()) {
				zugliste.backMove();
			}
		};

		knoepfe.jumpToStart.ctAngepasst = true;
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
