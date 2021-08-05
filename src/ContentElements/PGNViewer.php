<?php

namespace Schachbulle\ContaoPgnviewerBundle\ContentElements;

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
 * Class PGNViewer 
 *
 * @copyright  Wilfried Krebbers 2012 
 * @author     Wilfried Krebbers 
 * @package    pgn_viewer
 */
class PGNViewer extends ContentElement
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_pgnviewer';

    /**
     * Return if the file does not exist
     * @return string
     */
    public function generate() {
        // Return if there is no file
        if ($this->pgn_file == '') {
            return '';
        }

        $objFile = \FilesModel::findByUuid($this->pgn_file);

        if ($objFile === null) {
            if (!\Validator::isUuid($this->pgn_file)) {
                return '<p class="error">' . $GLOBALS['TL_LANG']['ERR']['version2format'] . '</p>';
            }

            return '';
        }

        if ($this->pgn_download) {
            $allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

            // if the file type is not allowed, don't show download
            if (!in_array($objFile->extension, $allowedDownload)) {
                //return '';
                $this->pgn_download = false;
            } else {

                $file = \Input::get('file', true);

                // Send the file to the browser and do not send a 404 header (see #4632)
                if ($file != '' && $file == $objFile->path) {
                    \Controller::sendFileToBrowser($file);
                }
            }
        }
        $this->pgn_file = $objFile->path;
        return parent::generate();
    }

    /**
     * Generate module
     */
    protected function compile() {

        /* Partiedaten auslesen */
        $pgn_file = $this->pgn_file;
        if (!file_exists($pgn_file)) {
            return;
        }
        $fp = new File($pgn_file);
        $inhalt = $fp->getContent();
        if (!$inhalt) {
            return;
        }

        /* Anzahl der Partien ermitteln und Tags für jede Partie speichern */
        $tags = $testtags = array();
        $partien_anz = 0;
        $leerzeile = $this->LeerzeileSuchen($inhalt, 0);
        /* PGN-Datei nach Tags durchsuchen */
        for ($i = 0; $i < strlen($inhalt); $i++) {
            if ($inhalt[$i] == '[') /* Tag beginnt */ {
                $tag_start = $i;
                $tag_ende = strpos($inhalt, ']', $i + 1);  /* Tag endet */
                if ($tag_ende !== false) {
                    $tag_wert_start = strpos($inhalt, "\"", $tag_start);
                    $tag_wert_ende = strpos($inhalt, "\"", $tag_wert_start + 1);
                    $tag_wert = substr($inhalt, $tag_wert_start + 1, $tag_wert_ende - ($tag_wert_start + 1));
                    $tag = trim(substr($inhalt, $tag_start + 1, $tag_wert_start - ($tag_start + 1)));
                    if (strpos($tag_wert, '?') === 0) {
                        $tag_wert = '';
                    }
                    if ($tag == 'Date') {
                        /* Datum umstellen und '??' löschen */
                        $tag_date = $tag_wert;
                        if ($tag_date && strpos($tag_date, '.') > 2) {
                            $day = substr($tag_date, 8, 2);
                            if (strpos($day, '?') !== 0) {
                                $newdate = $day . '.';
                            }
                            $month = substr($tag_date, 5, 2);
                            if (strpos($month, '?') !== 0) {
                                $newdate = $newdate . $month . '.';
                            }
                            $year = substr($tag_date, 0, 4);
                            if (strpos($year, '?') !== 0) {
                                $newdate = $newdate . $year;
                            }
                            $tag_date = $newdate;
                            unset($newdate);
                            $tag_wert = $tag_date;
                        }
                    }
                    $onegametags[strtolower($tag)] = $tag_wert;
                    $i = $tag_ende;
                }
            } elseif ($i >= $leerzeile) /* Notation beginnt mit einer Leerzeile nach dem Tag-Header. Diese wird übersprungen und die nächste Partie (Tag) gesucht */ {
                $tags[] = $onegametags;
                unset($onegametags);
                $partien_anz++;
                /* nächste Partie suchen */
                $pos_a = strpos($inhalt, '[', $i);
                if ($pos_a !== false) {
                    $i = $pos_a - 1;
                    $leerzeile = $this->LeerzeileSuchen($inhalt, $i + 1);
                } else {
                    break;
                }
            }
        }
        $this->Template->partiedaten = array_values($tags);

        /* Zuglistenformat einstellen */
        if ($this->pgn_moveformat) {
            $this->Template->moveformat = 'main_on_own_line';
        } else {
            $this->Template->moveformat = '';
        }

        /* Festlegen was von den Partiedaten angezeigt werden soll */
        $arrHeader = $this->pgn_gamestat;
        $this->Template->showevent = $this->ShowHeader($arrHeader, 'event');
        $this->Template->showsite = $this->ShowHeader($arrHeader, 'site');
        $this->Template->showdate = $this->ShowHeader($arrHeader, 'date');
        $this->Template->showround = $this->ShowHeader($arrHeader, 'round');
        $this->Template->showwhite = $this->ShowHeader($arrHeader, 'white');
        $this->Template->showblack = $this->ShowHeader($arrHeader, 'black');
        $this->Template->showresult = $this->ShowHeader($arrHeader, 'result');
        $this->Template->showeco = $this->ShowHeader($arrHeader, 'eco');
        $this->Template->showeloWhite = $this->ShowHeader($arrHeader, 'elo_w');
        $this->Template->showeloBlack = $this->ShowHeader($arrHeader, 'elo_b');
        $this->Template->showannotator = $this->ShowHeader($arrHeader, 'annotator');
        $this->Template->showplycount = $this->ShowHeader($arrHeader, 'plycount');

        /* Brettkoordinaten anzeigen? */
        $this->Template->show_coordinates = false;
        if ($this->pgn_coordinates) {
            $this->Template->show_coordinates = true;
        }

        /* Autoscroll? */
        $this->Template->autoscroll = false;
        if ($this->pgn_autoscroll) {
            $this->Template->autoscroll = true;
        }

        /* Sound? */
        $this->Template->sound = false;
        if ($this->pgn_sound && $GLOBALS['TL_CONFIG']['pgnviewer_sound'] == true) {
            $this->Template->sound = true;
        }

        /* Download der PGN-Datei */
        if ($this->pgn_download) {
            $objFile = new \File($this->pgn_file, true);

            if ($this->pgn_linkTitle == '') {
                $this->pgn_linkTitle = $objFile->basename;
            }

            $strHref = \Environment::get('request');

            // Remove an existing file parameter (see #5683)
            if (preg_match('/(&(amp;)?|\?)file=/', $strHref)) {
                $strHref = preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
            }

            $strHref .= (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($strHref, '?') !== false) ? '&amp;' : '?') . 'file=' . \System::urlEncode($objFile->value);

            $this->Template->link = $this->pgn_linkTitle;
            $this->Template->title = specialchars($this->pgn_titleText ? : $this->pgn_linkTitle);
            $this->Template->href = $strHref;
            $this->Template->filesize = $this->getReadableSize($objFile->filesize, 1);
            $this->Template->icon = 'bundles/contaopgnviewer/images/iconPGN.gif';
            $this->Template->mime = $objFile->mime;
            $this->Template->extension = $objFile->extension;
            $this->Template->path = $objFile->dirname;
        }
        /* ---------------------- */

        /**
         * JavaScript files
         */
        switch ($GLOBALS['TL_CONFIG']['pgnviewer_notationlang']) {
            case 'de':
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/notation-de.json';
                break;
            case 'pl':
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/notation-pl.json';
                break;
            case 'fig_d':
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/notation-fig_dark.json';
                break;
            case 'fig_l':
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/notation-fig_light.json';
                break;
            case 'cz':
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/notation-cz.json';
                break;
            case 'nl':
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/notation-nl.json';
                break;
            case 'fr':
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/notation-fr.json';
                break;
            case 'es':
                $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/notation-es.json';
                break;
        }

        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/pgnyui.js';
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaopgnviewer/js/pgnviewer.js';

        return;
    }

    protected function ShowHeader($arrHeader, $tag) {
        if (strpos($arrHeader, $tag) != FALSE) {
            return True;
        } else {
            return False;
        }
    }

    protected function LeerzeileSuchen($zeichenkette, $pos) {
        $lz_1 = strpos($zeichenkette, "\n\r\n", $pos);
        $lz_2 = strpos($zeichenkette, "\n\n", $pos);
        if ($lz_1 === false) {
            if ($lz_2 === false) {
                return strlen($zeichenkette) - 1;
            } else {
                return $lz_2;
            }
        } else {
            if ($lz_2 === false) {
                return $lz_1;
            }
        }
        return min($lz_1, $lz_2);
    }

}
