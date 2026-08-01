<?php

/*
 * This file is part of schachbulle/contao-pgnviewer-bundle.
 *
 * (c) Wilfried Krebbers, Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

/**
 * Paletten
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = ($GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] ?? '') . ';{pgnviewer:hide},pgnviewer_notationlang,pgnviewer_sound';

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
