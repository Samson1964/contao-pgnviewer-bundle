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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_content']['modul_config']     		= 'Element configuration';
$GLOBALS['TL_LANG']['tl_content']['download_legend']     	= 'Download settings';
$GLOBALS['TL_LANG']['tl_content']['pgn_file']     			= array('PGN file','Please choose a PGN file');
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize']     		= array('Select size figure','Please select the size of the figures (in px)');
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset']     		= array('Select pieces set','Please select the pieces set');
$GLOBALS['TL_LANG']['tl_content']['pgn_pause']     			= array('Pause between moves','The amount of time in milliseconds to wait between moves whilst autoplaying games');
$GLOBALS['TL_LANG']['tl_content']['pgn_moveformat']     	= array('Output formatted moves','Display the main line moves on their own line with the annotations/variations indented below the main line.');
$GLOBALS['TL_LANG']['tl_content']['pgn_boardfirst']     	= array('Show board before Move List','Show at first the game board, then output the Move List');
$GLOBALS['TL_LANG']['tl_content']['pgn_backlink'] 	    	= array('Show Backlink to Chess Tempo','Chess Tempo PGN Viewer is licensed under a Creative Commons License. You are able to use the viewer for free on your non-commercial web site on the condition that you place a visible link to http://chesstempo.com on the page where you are using the viewer. ');
$GLOBALS['TL_LANG']['tl_content']['pgn_coordinates']     	= array('Show coordinates','Shows the coordinates of the side of the board.');
$GLOBALS['TL_LANG']['tl_content']['pgn_autoscroll']     	= array('Autoscroll activate','The Move List is scroll automatically.');
$GLOBALS['TL_LANG']['tl_content']['pgn_sound']     	= array('Sound activate','The Moves are accompanied acoustically.');
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat']     		= array('Game data show','Select game data to be displayed');
$GLOBALS['TL_LANG']['tl_content']['pgn_download']			= array('Download the PGN-File','Enable to Download the PGN-File. ATTENTION! In the settings under \'Download file types\' insert file type \'pgn\'.');
$GLOBALS['TL_LANG']['tl_content']['pgn_titleText']			= array('Link-Title','The link title is added as title attribute in the HTML markup');
$GLOBALS['TL_LANG']['tl_content']['pgn_linkTitle']			= array('Link-Text','The link text will be displayed instead of the target URL.');
$GLOBALS['TL_LANG']['tl_content']['pgn_notationsize']['0'] = "Height of the notation window";
$GLOBALS['TL_LANG']['tl_content']['pgn_notationsize']['1'] = "Restricts the notation Window on the specified max. Height (in Pixel). Set to 0 to cancel the restriction.";


$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['merida']	= 'Merida';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['leipzig'] = 'Leipzig';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['maya']	= 'Maya';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['condal']	= 'Condal';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['case']	= 'Case';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['kingdom']	= 'Kingdom';

$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['event']		= 'Event';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['site']		= 'Site';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['date']		= 'Date';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['round']		= 'Round';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['white']		= 'White';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['black']		= 'Black';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['result']		= 'Result';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['eco']			= 'ECO';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['elo_w']		= 'ELO White';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['elo_b']		= 'ELO Black';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['plycount']	= 'Number of half-moves';
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat_option']['annotator']	= 'Annotator';
?>