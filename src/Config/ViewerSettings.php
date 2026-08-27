<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-pgnviewer-bundle.
 *
 * (c) Wilfried Krebbers, Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoPgnviewerBundle\Config;

use Contao\Config;
use Contao\StringUtil;

/**
 * Verwaltet die Einstellungen, die es sowohl global als auch je Inhaltselement
 * gibt.
 *
 * Zehn Angaben lassen sich unter Einstellungen → PGNViewer einmal für die ganze
 * Installation festlegen. Zusätzlich steht dort, welche davon ein Redakteur im
 * einzelnen Inhaltselement überschreiben darf. Ist ein Feld nicht freigegeben,
 * gilt überall der globale Wert, und das Feld erscheint im Inhaltselement gar
 * nicht erst.
 *
 * Diese Klasse hält die Zuordnung und die Auswahllisten an einer Stelle, damit
 * die beiden DCA-Dateien und das Inhaltselement dieselben Werte verwenden.
 */
final class ViewerSettings
{
	/**
	 * Feld im Inhaltselement => zugehörige globale Einstellung.
	 *
	 * @var array<string, string>
	 */
	public const FIELDS = array
	(
		'pgn_pieceset' => 'pgnviewer_pieceset',
		'pgn_piecesize' => 'pgnviewer_piecesize',
		'pgn_boardstyle' => 'pgnviewer_boardstyle',
		'pgn_coordinates' => 'pgnviewer_coordinates',
		'pgn_gamestat' => 'pgnviewer_gamestat',
		'pgn_boardfirst' => 'pgnviewer_boardfirst',
		'pgn_notationsize' => 'pgnviewer_notationsize',
		'pgn_pause' => 'pgnviewer_pause',
		'pgn_backlink' => 'pgnviewer_backlink',
		'pgn_download' => 'pgnviewer_download',
	);

	/**
	 * Werte, die gelten, solange in den Einstellungen nichts gespeichert wurde.
	 *
	 * Sie sind zugleich die Voreinstellung der Felder im Einstellungsdialog.
	 * Bestehende Inhaltselemente berührt das nicht: Solange ein Feld
	 * überschreibbar ist — und das sind bis zum ersten Speichern alle —, gilt
	 * dort weiterhin der gespeicherte Wert des Elements.
	 *
	 * @var array<string, string>
	 */
	public const FALLBACKS = array
	(
		'pgn_pieceset' => 'merida',
		'pgn_piecesize' => '46',
		'pgn_boardstyle' => 'blue',
		'pgn_coordinates' => '1',
		'pgn_gamestat' => '1',
		'pgn_boardfirst' => 'wrap',
		'pgn_notationsize' => '300',
		'pgn_pause' => '800',
		'pgn_backlink' => '1',
		'pgn_download' => '',
	);

	/**
	 * Die Figurensätze des Betrachters.
	 *
	 * Bis auf „merida-gradient“ liegt zu jedem eine eigene CSS-Datei im Bundle,
	 * die der Betrachter bei Bedarf nachlädt.
	 *
	 * @var array<int, string>
	 */
	public const PIECE_SETS = array('merida', 'merida-gradient', 'leipzig', 'maya', 'case', 'kingdom', 'alpha', 'wiki', 'celtic', 'fantasy', 'goodcomp', 'goodcomp-gradient', 'kosal', 'spatial', 'eyes', 'skulls');

	/**
	 * Kantenlänge einer Figur in Pixel; das Brett wird achtmal so breit.
	 *
	 * @var array<int, string>
	 */
	public const PIECE_SIZES = array('20', '24', '29', '35', '40', '46', '56', '64', '72', '80');

	/**
	 * Brettstile: erst die einfarbigen, dann die mit Bild.
	 *
	 * @var array<int, string>
	 */
	public const BOARD_STYLES = array('blue', 'blue-gradient', 'cadet-grey-gradient', 'teal-gradient', 'green', 'green-white', 'brown', 'brown-cream', 'grey', 'grey-gradient', 'grey-diagonals', 'light-grey', 'white-grey', 'wood-light', 'wood-medium', 'wood-dark', 'wood-dark2', 'wood-pine', 'marble-brown', 'marble-green', 'granite', 'cracked-stone', 'stucco', 'sand-snow', 'metal', 'gold-silver', 'leather', 'fabric', 'grass', 'camouflage', 'green-plasma');

	/**
	 * Anordnung von Brett und Zugliste.
	 *
	 * Der Wert „1“ stammt aus dem früheren Kästchen „Zugliste unter dem Brett“
	 * und bleibt gültig, damit bestehende Elemente ihre Einstellung behalten.
	 *
	 * @var array<int, string>
	 */
	public const LAYOUTS = array('', '1', 'wrap');

	/**
	 * Prüft, ob ein Feld im Inhaltselement überschrieben werden darf.
	 *
	 * Solange in den Einstellungen nichts gespeichert wurde, sind alle Felder
	 * freigegeben — sonst würden bestehende Installationen nach einer
	 * Aktualisierung plötzlich alle Angaben ihrer Inhaltselemente übergehen.
	 * Wurde die Liste dagegen gespeichert und ist leer, ist das eine bewusste
	 * Entscheidung und wird auch so befolgt.
	 *
	 * @param string $strField Feldname im Inhaltselement, etwa „pgn_pieceset“
	 *
	 * @return bool true, wenn das Inhaltselement den globalen Wert überstimmen darf
	 */
	public static function isOverridable(string $strField): bool
	{
		$varAllowed = Config::get('pgnviewer_overridable');

		if (null === $varAllowed)
		{
			return true;
		}

		return \in_array($strField, StringUtil::deserialize($varAllowed, true), true);
	}

	/**
	 * Liefert den global eingestellten Wert eines Feldes.
	 *
	 * @param string $strField Feldname im Inhaltselement, etwa „pgn_pieceset“
	 *
	 * @return string Der globale Wert, oder der Rückfallwert, solange in den
	 *                Einstellungen nichts gespeichert wurde
	 */
	public static function getGlobal(string $strField): string
	{
		if (!isset(self::FIELDS[$strField]))
		{
			return '';
		}

		$varValue = Config::get(self::FIELDS[$strField]);

		// Nur ein fehlender Eintrag greift auf den Rückfallwert zurück. Ein
		// leerer Wert ist eine gültige Angabe: bei den Kästchen heißt er
		// „nein“, beim Brettstil „Voreinstellung des Betrachters“.
		if (null === $varValue)
		{
			return self::FALLBACKS[$strField];
		}

		return (string) $varValue;
	}
}
