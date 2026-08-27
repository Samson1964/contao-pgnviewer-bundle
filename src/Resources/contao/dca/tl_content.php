<?php

/*
 * This file is part of schachbulle/contao-pgnviewer-bundle.
 *
 * (c) Wilfried Krebbers, Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

use Contao\Config;
use Schachbulle\ContaoPgnviewerBundle\Config\ViewerSettings;

/**
 * Paletten
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'pgn_download';

// Zehn Angaben lassen sich global vorgeben. Ist eine davon in den Einstellungen
// nicht zum Überschreiben freigegeben, erscheint sie hier gar nicht erst — sonst
// ließen sich Werte eintragen, die nachher niemand beachtet.
$arrPgnFields = array_filter
(
	array('pgn_file', 'pgn_pieceset', 'pgn_piecesize', 'pgn_boardstyle', 'pgn_coordinates', 'pgn_gamestat', 'pgn_boardfirst', 'pgn_moveformat', 'pgn_notationsize', 'pgn_pause', 'pgn_sound', 'pgn_backlink'),
	static function (string $strField): bool
	{
		return !isset(ViewerSettings::FIELDS[$strField]) || ViewerSettings::isOverridable($strField);
	}
);

// Der Downloadbereich braucht eine eigene Behandlung, weil an "pgn_download"
// zwei weitere Felder hängen. Ist das Kästchen gesperrt und eingeschaltet,
// bleiben Link-Text und Link-Title stehen: Ihre Beschriftung gehört zur
// einzelnen Datei und lässt sich nicht global vorgeben. Ist es gesperrt und
// ausgeschaltet, entfällt der ganze Bereich.
if (ViewerSettings::isOverridable('pgn_download'))
{
	$strPgnDownload = ';{download_legend},pgn_download';
}
elseif (ViewerSettings::getGlobal('pgn_download'))
{
	$strPgnDownload = ';{download_legend},pgn_linkTitle,pgn_titleText';
}
else
{
	$strPgnDownload = '';
}

// Das Feld "guests" gibt es nur in Contao 4.13; in Contao 5 wird es von der
// Palette stillschweigend übergangen
$GLOBALS['TL_DCA']['tl_content']['palettes']['pgnviewer'] = '{type_legend},type,headline;{modul_config},' . implode(',', $arrPgnFields) . $strPgnDownload . ';{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['pgn_download'] = 'pgn_linkTitle,pgn_titleText';

/**
 * Felder
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_file'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_file'],
	'exclude'   => true,
	'inputType' => 'fileTree',
	'eval'      => array
	(
		'mandatory'      => true,
		'files'          => true,
		'filesOnly'      => true,
		'extensions'     => 'pgn',
		'fieldType'      => 'radio',
		'tl_class'       => 'clr',
		'submitOnChange' => true
	),
	'sql'       => "binary(16) NULL"
);

// Die Werte entsprechen den Figurensätzen des Chesstempo-Viewers. Bis auf
// "merida-gradient" liegt zu jedem Satz eine eigene CSS-Datei im Bundle, die
// das Inhaltselement nur bei Bedarf einbindet.
// Ein neues Element startet mit dem global eingestellten Wert; das gilt für alle
// acht Felder, die es auch in den Einstellungen gibt.
$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_pieceset'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset'],
	'default'   => ViewerSettings::getGlobal('pgn_pieceset'),
	'inputType' => 'select',
	'options'   => ViewerSettings::PIECE_SETS,
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option'],
	'eval'      => array('mandatory' => true, 'tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

// Der Viewer bekommt eine Brettgröße; die Figuren skalieren mit. Der Wert wird
// weiterhin als Figurengröße gepflegt, damit die Einstellung bestehender
// Elemente unverändert weiter gilt: das Brett ist achtmal so breit.
$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_piecesize'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize'],
	'default'   => ViewerSettings::getGlobal('pgn_piecesize'),
	'inputType' => 'select',
	'options'   => ViewerSettings::PIECE_SIZES,
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option'],
	'eval'      => array('mandatory' => true, 'tl_class' => 'w50'),
	'sql'       => "int(10) unsigned NOT NULL default '46'"
);

// Die Brettstile mit Bildern (Holz, Stein, Leder …) brauchen die Bilddateien
// unter pgnviewer/images/board-backgrounds/, die im Bundle liegen.
$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_boardstyle'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle'],
	'default'   => ViewerSettings::getGlobal('pgn_boardstyle'),
	'inputType' => 'select',
	// Erst die einfarbigen Bretter, dann die mit Bild
	'options'   => ViewerSettings::BOARD_STYLES,
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option'],
	'eval'      => array('includeBlankOption' => true, 'tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_coordinates'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_coordinates'],
	'default'   => ViewerSettings::getGlobal('pgn_coordinates'),
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50 clr'),
	'sql'       => "char(1) NOT NULL default ''"
);

// Früher eine Mehrfachauswahl einzelner Angaben; der Viewer stellt die Kopfzeile
// jetzt selbst zusammen, deshalb bleibt nur noch die Frage, ob sie erscheint.
// Der alte serialisierte Wert ist nicht leer und gilt damit als „ja“.
$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_gamestat'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat'],
	'default'   => ViewerSettings::getGlobal('pgn_gamestat'),
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

// Früher ein Kästchen „Zugliste unter dem Brett“. Damit die Einstellung
// bestehender Elemente erhalten bleibt, ist der alte Wert „1“ weiterhin eine
// gültige Auswahl und bedeutet unverändert dasselbe.
$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_boardfirst'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_boardfirst'],
	'default'   => ViewerSettings::getGlobal('pgn_boardfirst'),
	'inputType' => 'select',
	'options'   => ViewerSettings::LAYOUTS,
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_boardfirst_option'],
	'eval'      => array('tl_class' => 'w50 clr'),
	'sql'       => "varchar(8) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_moveformat'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_moveformat'],
	'default'   => '',
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_notationsize'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_notationsize'],
	'default'   => ViewerSettings::getGlobal('pgn_notationsize'),
	'inputType' => 'text',
	'eval'      => array('mandatory' => true, 'rgxp' => 'digit', 'tl_class' => 'w50 clr'),
	'sql'       => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_pause'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_pause'],
	'default'   => ViewerSettings::getGlobal('pgn_pause'),
	'inputType' => 'text',
	'eval'      => array('mandatory' => true, 'tl_class' => 'w50', 'rgxp' => 'digit'),
	'sql'       => "int(10) unsigned NOT NULL default '800'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_sound'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_sound'],
	'default'   => '',
	'inputType' => 'checkbox',
	// Der Ton lässt sich systemweit in den Einstellungen abschalten; ist er dort
	// aus, wird das Feld nur noch angezeigt und nicht mehr zur Auswahl angeboten
	'eval'      => array('tl_class' => 'w50 clr', 'disabled' => !Config::get('pgnviewer_sound')),
	'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_backlink'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_backlink'],
	'default'   => ViewerSettings::getGlobal('pgn_backlink'),
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_download'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_download'],
	'default'   => ViewerSettings::getGlobal('pgn_download'),
	'inputType' => 'checkbox',
	'eval'      => array('submitOnChange' => true),
	'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_titleText'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_titleText'],
	'inputType' => 'text',
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_linkTitle'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_linkTitle'],
	'inputType' => 'text',
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);
