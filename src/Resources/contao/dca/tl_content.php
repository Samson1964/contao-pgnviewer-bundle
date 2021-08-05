<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Wilfried Krebbers 2012 
 * @author     Wilfried Krebbers 
 * @package    pgn_viewer 
 * @license    LGPL 
 * @filesource
 */

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'pgn_download';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'pgn_autoscroll';
$GLOBALS['TL_DCA']['tl_content']['palettes']['pgnviewer'] = '{type_legend},type,headline,;{modul_config},pgn_file,pgn_gamestat,pgn_pieceset,pgn_piecesize,pgn_pause,pgn_moveformat,pgn_boardfirst,pgn_backlink,pgn_coordinates,pgn_sound,pgn_autoscroll;{download_legend},pgn_download;{protected_legend:hide},protected;{expert_legend:hide},guest,cssID,space;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['pgn_download'] = 'pgn_linkTitle,pgn_titleText';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['pgn_autoscroll'] = 'pgn_notationsize';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_piecesize'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize'],
    'default'   => '46',
    'inputType' => 'select',
    'options'   => array('20', '24', '29', '35', '40', '46'),
    'eval'      => array('mandatory' => true, 'tl_class' => 'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '46'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_pieceset'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset'],
    'default'   => 'merida',
    'inputType' => 'select',
    'options'   => array('merida', 'leipzig', 'maya', 'condal', 'case', 'kingdom'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option'],
    'eval'      => array('mandatory' => true, 'tl_class' => 'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_file'] = array(
    'label'         => &$GLOBALS['TL_LANG']['tl_content']['pgn_file'],
    'inputType'     => 'fileTree',
    'eval'          => array(
                    'mandatory' => true,
                    'files'     => true,
                    'filesOnly' => true,
                    'extensions' => 'pgn',
                    'fieldType' => 'radio',
                    'tl_class' => 'clr',
                    'submitOnChange' => true),
    'exclude'       => true,
    'sql'           => "binary(16) NULL",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_pause'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_pause'],
    'default'   => '800',
    'inputType' => 'text',
    'eval'      => array('mandatory' => true, 'tl_class' => 'w50', 'rgxp' => 'digit'),
    'sql'       => "int(10) unsigned NOT NULL default '800'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_moveformat'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_moveformat'],
    'default'   => false,
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50 clr'),
    'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_boardfirst'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_boardfirst'],
    'default'   => false,
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_backlink'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_backlink'],
    'default'   => true,
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_coordinates'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_coordinates'],
    'default'   => false,
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_autoscroll'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_autoscroll'],
    'default'   => '',
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'clr', 'isBoolean' => true, 'submitOnChange' => true),
    'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_notationsize'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_notationsize'],
    'inputType' => 'text',
    'default'   => '0',    
    'eval'      => array('mandatory' => true, 'rgxp' => 'digit','tl_class' => 'w50'),
    'sql'       => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_sound'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_sound'],
    'default'   => false,
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50', 'disabled' => false),
    'sql'       => "char(1) NOT NULL default ''"
);
if ($GLOBALS['TL_CONFIG']['pgnviewer_sound'] == false)
{
	$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_sound']['eval']['disabled'] = true;
}


$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_gamestat'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat'],
    'default'   => false,
    'inputType' => 'checkbox',
    'options'   => array('event', 'site', 'date', 'round', 'white', 'black', 'result', 'eco', 'elo_w', 'elo_b', 'annotator', 'plycount'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option'],
    'eval'      => array('multiple' => 'true', 'tl_class' => 'w50 clr'),
    'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_download'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_download'],
    'default'   => '',
    'inputType' => 'checkbox',
    'eval'	=> array('submitOnChange' => true),
    'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_titleText'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_titleText'],
    'inputType' => 'text',
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['pgn_linkTitle'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['pgn_linkTitle'],
    'inputType' => 'text',
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "varchar(255) NOT NULL default ''"
);

?>