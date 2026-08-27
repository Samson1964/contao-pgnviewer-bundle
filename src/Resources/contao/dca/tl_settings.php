<?php

/*
 * This file is part of schachbulle/contao-pgnviewer-bundle.
 *
 * (c) Wilfried Krebbers, Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

use Contao\System;
use Schachbulle\ContaoPgnviewerBundle\Config\ViewerSettings;

/*
 * Die Auswahllisten der Voreinstellungen tragen dieselben Bezeichnungen wie im
 * Inhaltselement. Der Einstellungsdialog laedt von sich aus nur seine eigene
 * Sprachdatei, deshalb wird die des Inhaltselements hier nachgezogen.
 */
System::loadLanguageFile('tl_content');

/**
 * Paletten
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = ($GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] ?? '')
	. ';{pgnviewer:hide},pgnviewer_notationlang,pgnviewer_sound'
	. ';{pgnviewer_defaults_legend:hide},pgnviewer_pieceset,pgnviewer_piecesize,pgnviewer_boardstyle,pgnviewer_coordinates,pgnviewer_gamestat,pgnviewer_boardfirst,pgnviewer_notationsize,pgnviewer_pause,pgnviewer_backlink,pgnviewer_download,pgnviewer_overridable';

/**
 * Felder
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_notationlang'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_notationlang'],
	'default'   => 'en',
	'inputType' => 'select',
	'options'   => array('en', 'de', 'fr', 'nl', 'pl', 'es', 'cz', 'fig_l', 'fig_d'),
	'reference' => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_notationlang_option'],
	'eval'      => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_sound'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_sound'],
	'default'   => '',
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50')
);

/**
 * Voreinstellungen für alle Inhaltselemente
 *
 * Die Beschriftungen sind dieselben wie im Inhaltselement; das erspart doppelt
 * gepflegte Texte und macht den Zusammenhang sichtbar.
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_pieceset'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_pieceset'],
	'default'   => ViewerSettings::FALLBACKS['pgn_pieceset'],
	'inputType' => 'select',
	'options'   => ViewerSettings::PIECE_SETS,
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option'],
	'eval'      => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_piecesize'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_piecesize'],
	'default'   => ViewerSettings::FALLBACKS['pgn_piecesize'],
	'inputType' => 'select',
	'options'   => ViewerSettings::PIECE_SIZES,
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option'],
	'eval'      => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_boardstyle'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_boardstyle'],
	'default'   => ViewerSettings::FALLBACKS['pgn_boardstyle'],
	'inputType' => 'select',
	'options'   => ViewerSettings::BOARD_STYLES,
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option'],
	'eval'      => array('includeBlankOption' => true, 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_boardfirst'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_boardfirst'],
	'default'   => ViewerSettings::FALLBACKS['pgn_boardfirst'],
	'inputType' => 'select',
	'options'   => ViewerSettings::LAYOUTS,
	'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_boardfirst_option'],
	'eval'      => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_coordinates'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_coordinates'],
	'default'   => ViewerSettings::FALLBACKS['pgn_coordinates'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50 clr')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_gamestat'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_gamestat'],
	'default'   => ViewerSettings::FALLBACKS['pgn_gamestat'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_notationsize'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_notationsize'],
	'default'   => ViewerSettings::FALLBACKS['pgn_notationsize'],
	'inputType' => 'text',
	'eval'      => array('rgxp' => 'digit', 'tl_class' => 'w50 clr')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_pause'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_pause'],
	'default'   => ViewerSettings::FALLBACKS['pgn_pause'],
	'inputType' => 'text',
	'eval'      => array('rgxp' => 'digit', 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_backlink'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_backlink'],
	'default'   => ViewerSettings::FALLBACKS['pgn_backlink'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50 clr')
);

// Ist dieses Feld gesperrt und eingeschaltet, bleiben Link-Text und Link-Title
// trotzdem im Inhaltselement stehen — die Beschriftung gehört zur einzelnen
// Datei und lässt sich nicht sinnvoll global vorgeben.
$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_download'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_download'],
	'default'   => ViewerSettings::FALLBACKS['pgn_download'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50')
);

/**
 * Welche der Voreinstellungen ein Redakteur im Inhaltselement überstimmen darf.
 *
 * Nicht freigegebene Felder erscheinen im Inhaltselement gar nicht erst — sonst
 * ließen sich dort Werte eintragen, die nachher niemand beachtet.
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_overridable'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_overridable'],
	'default'   => array_keys(ViewerSettings::FIELDS),
	'inputType' => 'checkbox',
	'options'   => array_keys(ViewerSettings::FIELDS),
	'reference' => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_overridable_option'],
	'eval'      => array('multiple' => true, 'tl_class' => 'clr')
);
