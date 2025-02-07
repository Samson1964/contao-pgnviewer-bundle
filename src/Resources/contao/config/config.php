<?php

/**
 * -------------------------------------------------------------------------
 * Globale Konfiguration abfragen und ggfs. anlegen
 * -------------------------------------------------------------------------
 */

if(!isset($GLOBALS['TL_CONFIG']['disableAlias'])) $GLOBALS['TL_CONFIG']['disableAlias'] = false;

/**
 * -------------------------------------------------------------------------
 * Inhaltselemente
 * -------------------------------------------------------------------------
 */
$GLOBALS['TL_CTE']['chess']['pgnviewer'] = 'Schachbulle\ContaoPgnviewerBundle\ContentElements\PGNViewer';
