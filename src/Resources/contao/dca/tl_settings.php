<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * PHP version 5
 * @copyright  Wilfried Krebbers 2012 
 * @author     Wilfried Krebbers 
 * @package    pgn_viewer 
 * @license    LGPL 
 * @filesource
 */

/**
 * palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{pgnviewer:hide},pgnviewer_notationlang,pgnviewer_sound';

/**
 * fields
 */

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_notationlang'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_notationlang'],
    'default'   => 'en', 'inputType' => 'select',
    'options'   => array('en', 'de', 'fr', 'nl','pl', 'es', 'cz', 'fig_l', 'fig_d'),
    'reference' => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_notationlang_option']
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['pgnviewer_sound'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['pgnviewer_sound'],
    'default'   => false,
    'inputType' => 'checkbox'
);
