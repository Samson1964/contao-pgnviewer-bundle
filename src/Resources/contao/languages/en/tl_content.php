<?php

/*
 * This file is part of schachbulle/contao-pgnviewer-bundle.
 *
 * (c) Wilfried Krebbers, Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_LANG']['tl_content']['modul_config'] = 'Element configuration';
$GLOBALS['TL_LANG']['tl_content']['download_legend'] = 'Download settings';

$GLOBALS['TL_LANG']['tl_content']['pgn_file'] = array('PGN file', 'Please choose a PGN file. It may contain any number of games.');
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset'] = array('Piece set', 'Please select the piece set.');
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize'] = array('Piece size', 'Edge length of a piece in pixels. The board is eight times as wide.');
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle'] = array('Board style', 'Appearance of the squares, from plain colours to wood, marble or leather. Without a selection the viewer keeps its default (blue with gradient).');
$GLOBALS['TL_LANG']['tl_content']['pgn_coordinates'] = array('Show coordinates', 'Shows the file and rank labels along the left and bottom edge of the board.');
$GLOBALS['TL_LANG']['tl_content']['pgn_gamestat'] = array('Show game details', 'Shows the header above the move list with names, ratings, result, event, site, round, ECO code and date.');
$GLOBALS['TL_LANG']['tl_content']['pgn_boardfirst'] = array('Move list below the board', 'Without the checkmark the move list sits to the right of the board.');
$GLOBALS['TL_LANG']['tl_content']['pgn_moveformat'] = array('Two-column move list', 'Shows the moves in two columns. Without the checkmark the main line is listed one move per line with variations and comments indented.');
$GLOBALS['TL_LANG']['tl_content']['pgn_notationsize'] = array('Height of the move list', 'Limits the move list to the given height in pixels and scrolls beyond that. Enter 0 to remove the limit.');
$GLOBALS['TL_LANG']['tl_content']['pgn_pause'] = array('Pause between moves', 'Waiting time in milliseconds while autoplaying the game.');
$GLOBALS['TL_LANG']['tl_content']['pgn_sound'] = array('Enable sound', 'The moves are accompanied by a sound.');
$GLOBALS['TL_LANG']['tl_content']['pgn_backlink'] = array('Show Chess Tempo backlink', 'The Chess Tempo viewer is licensed under a Creative Commons licence. On non-commercial sites you may only use it if you place a visible link to chesstempo.com.');
$GLOBALS['TL_LANG']['tl_content']['pgn_download'] = array('Download the PGN file', 'Offers the file for download below the board. ATTENTION! The file type "pgn" has to be listed under "Allowed download file types" in the settings.');
$GLOBALS['TL_LANG']['tl_content']['pgn_titleText'] = array('Link title', 'The link title is added as title attribute in the HTML markup.');
$GLOBALS['TL_LANG']['tl_content']['pgn_linkTitle'] = array('Link text', 'The link text is shown instead of the file name.');

$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['merida'] = 'Merida';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['merida-gradient'] = 'Merida with gradient';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['leipzig'] = 'Leipzig';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['maya'] = 'Maya';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['case'] = 'Case';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['kingdom'] = 'Kingdom';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['alpha'] = 'Alpha';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['wiki'] = 'Wikipedia';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['celtic'] = 'Celtic';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['fantasy'] = 'Fantasy';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['goodcomp'] = 'Good Companion';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['goodcomp-gradient'] = 'Good Companion with gradient';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['kosal'] = 'Kosal';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['spatial'] = 'Spatial';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['eyes'] = 'Eyes';
$GLOBALS['TL_LANG']['tl_content']['pgn_pieceset_option']['skulls'] = 'Skulls';

$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['20'] = '20 pixels (board 160)';
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['24'] = '24 pixels (board 192)';
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['29'] = '29 pixels (board 232)';
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['35'] = '35 pixels (board 280)';
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['40'] = '40 pixels (board 320)';
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['46'] = '46 pixels (board 368)';
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['56'] = '56 pixels (board 448)';
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['64'] = '64 pixels (board 512)';
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['72'] = '72 pixels (board 576)';
$GLOBALS['TL_LANG']['tl_content']['pgn_piecesize_option']['80'] = '80 pixels (board 640)';

$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['blue'] = 'Blue';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['blue-gradient'] = 'Blue with gradient';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['cadet-grey-gradient'] = 'Cadet grey with gradient';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['teal-gradient'] = 'Teal with gradient';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['green'] = 'Green';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['green-white'] = 'Green and white';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['brown'] = 'Brown';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['brown-cream'] = 'Brown and cream';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['grey'] = 'Grey';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['grey-gradient'] = 'Grey with gradient';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['grey-diagonals'] = 'Grey with diagonals';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['light-grey'] = 'Light grey';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['white-grey'] = 'White and grey';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['wood-light'] = 'Light wood';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['wood-medium'] = 'Medium wood';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['wood-dark'] = 'Dark wood';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['wood-dark2'] = 'Dark wood with gradient';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['wood-pine'] = 'Pine';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['marble-brown'] = 'Brown marble';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['marble-green'] = 'Green marble';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['granite'] = 'Granite';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['cracked-stone'] = 'Cracked stone';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['stucco'] = 'Stucco';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['sand-snow'] = 'Sand and snow';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['metal'] = 'Metal';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['gold-silver'] = 'Gold and silver';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['leather'] = 'Leather';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['fabric'] = 'Fabric';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['grass'] = 'Grass';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['camouflage'] = 'Camouflage';
$GLOBALS['TL_LANG']['tl_content']['pgn_boardstyle_option']['green-plasma'] = 'Green plasma';
