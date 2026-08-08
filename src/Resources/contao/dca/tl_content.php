<?php

/*
 * This file is part of schachbulle/contao-pgnviewer-bundle.
 *
 * (c) Wilfried Krebbers, Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

use Contao\Config;

/**
 * Paletten
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'pgn_download';

// Das Feld "guests" gibt es nur in Contao 4.13; in Contao 5 wird es von der
// Palette stillschweigend übergangen
$GLOBALS['TL_DCA']['tl_content']['palettes']['pgnviewer'] = '{type_legend},type,headline;{modul_config},pgn_file,pgn_pieceset,pgn_piecesize,pgn_boardstyle,pgn_coordinates,pgn_gamestat,pgn_boardfirst,pgn_moveformat,pgn_notationsize,pgn_pause,pgn_sound,pgn_backlink;{download_legend},pgn_download;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
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
$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_pieceset'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset'],
	'default'   => 'merida',
	'inputType' => 'select',
	'options'   => array('merida', 'merida-gradient', 'leipzig', 'maya', 'case', 'kingdom', 'alpha', 'wiki', 'celtic', 'fantasy', 'goodcomp', 'goodcomp-gradient', 'kosal', 'spatial', 'eyes', 'skulls'),
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
	'default'   => '46',
	'inputType' => 'select',
	'options'   => array('20', '24', '29', '35', '40', '46', '56', '64', '72', '80'),
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option'],
	'eval'      => array('mandatory' => true, 'tl_class' => 'w50'),
	'sql'       => "int(10) unsigned NOT NULL default '46'"
);

// Die Brettstile mit Bildern (Holz, Stein, Leder …) brauchen die Bilddateien
// unter pgnviewer/images/board-backgrounds/, die im Bundle liegen.
$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_boardstyle'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle'],
	'default'   => '',
	'inputType' => 'select',
	// Erst die einfarbigen Bretter, dann die mit Bild
	'options'   => array('blue', 'blue-gradient', 'cadet-grey-gradient', 'teal-gradient', 'green', 'green-white', 'brown', 'brown-cream', 'grey', 'grey-gradient', 'grey-diagonals', 'light-grey', 'white-grey', 'wood-light', 'wood-medium', 'wood-dark', 'wood-dark2', 'wood-pine', 'marble-brown', 'marble-green', 'granite', 'cracked-stone', 'stucco', 'sand-snow', 'metal', 'gold-silver', 'leather', 'fabric', 'grass', 'camouflage', 'green-plasma'),
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option'],
	'eval'      => array('includeBlankOption' => true, 'tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_coordinates'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_coordinates'],
	'default'   => '',
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
	'default'   => '1',
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
	'default'   => '',
	'inputType' => 'select',
	'options'   => array('', '1', 'wrap'),
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
	'default'   => '0',
	'inputType' => 'text',
	'eval'      => array('mandatory' => true, 'rgxp' => 'digit', 'tl_class' => 'w50 clr'),
	'sql'       => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_pause'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_pause'],
	'default'   => '800',
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
	'default'   => '1',
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_download'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_download'],
	'default'   => '',
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
