<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-pgnviewer-bundle.
 *
 * (c) Wilfried Krebbers, Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoPgnviewerBundle\ContentElements;

use Contao\BackendTemplate;
use Contao\Config;
use Contao\ContentElement;
use Contao\Controller;
use Contao\Environment;
use Contao\File;
use Contao\FilesModel;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;

/**
 * Inhaltselement „PGNViewer“.
 *
 * Das Element bindet eine PGN-Datei aus der Dateiverwaltung ein und übergibt
 * sie an den PGN-Viewer von Chesstempo. Der Viewer ist ein eigenes
 * HTML-Element (<ct-pgn-viewer>), das Brett, Zugliste, Bedienknöpfe, Kopfzeile
 * und – bei mehreren Partien in einer Datei – die Auswahlliste selbst aufbaut.
 * Auf PHP-Seite bleibt deshalb nur, die Einstellungen als Attribute zu
 * übergeben und die passenden Dateien einzubinden.
 *
 * Alle Dateien des Viewers liegen im Bundle; zur Laufzeit wird kein fremder
 * Server angesprochen (siehe Resources/public/js/ct-setup.js).
 */
class PGNViewer extends ContentElement
{
	/**
	 * Template.
	 *
	 * @var string
	 */
	protected $strTemplate = 'ce_pgnviewer';

	/**
	 * Notationssprachen und die zugehörige Datei unter public/js/locale.
	 *
	 * Englisch fehlt bewusst: es ist die eingebaute Sprache des Viewers und
	 * braucht keine zusätzliche Datei.
	 *
	 * @var array<string, string>
	 */
	private const NOTATION_FILES = array
	(
		'de' => 'de',
		'fr' => 'fr',
		'nl' => 'nl',
		'pl' => 'pl',
		'es' => 'es',
		'cz' => 'cz',
		'fig_l' => 'fig_light',
		'fig_d' => 'fig_dark',
	);

	/**
	 * Bereitet das Element vor und liefert das fertige HTML.
	 *
	 * Vor dem Aufbau wird geprüft, ob überhaupt eine gültige Datei hinterlegt
	 * ist; ohne Datei gibt es nichts anzuzeigen. Ist der Download eingeschaltet,
	 * wird hier außerdem die Anforderung der Datei abgefangen und die Datei
	 * direkt an den Browser geschickt. Das muss vor dem Rendern passieren, weil
	 * dabei Header gesendet werden.
	 *
	 * Nebenwirkung: Die Eigenschaft pgn_file wird von der binären UUID auf den
	 * Dateipfad umgestellt, weil das Template den Pfad an den Viewer
	 * weiterreicht.
	 *
	 * @return string Das gerenderte Element, im Backend ein Platzhalter und eine
	 *                leere Zeichenkette, wenn keine (gültige) Datei hinterlegt
	 *                ist
	 */
	public function generate()
	{
		// Im Backend ergibt der Viewer keinen Sinn, dort genügt ein Platzhalter
		if ($this->isBackendRequest())
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . ($GLOBALS['TL_LANG']['CTE']['pgnviewer'][0] ?? 'PGNVIEWER') . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}

		if (!$this->pgn_file)
		{
			return '';
		}

		$objModel = FilesModel::findByUuid($this->pgn_file);

		if (null === $objModel)
		{
			return '';
		}

		if ($this->pgn_download)
		{
			$arrAllowed = StringUtil::trimsplit(',', strtolower((string) Config::get('allowedDownload')));

			if (!\in_array($objModel->extension, $arrAllowed, true))
			{
				// Der Dateityp ist in den Einstellungen nicht freigegeben, dann
				// wird der Downloadlink gar nicht erst angeboten
				$this->pgn_download = '';
			}
			else
			{
				$strFile = Input::get('file', true);

				// Die Datei ausliefern, ohne einen 404-Header zu senden (siehe #4632)
				if ($strFile && $strFile === $objModel->path)
				{
					Controller::sendFileToBrowser($strFile);
				}
			}
		}

		$this->pgn_file = $objModel->path;

		return parent::generate();
	}

	/**
	 * Stellt die Attribute für den Viewer zusammen und bindet die Dateien ein.
	 *
	 * Nebenwirkung: Die benötigten JavaScript- und CSS-Dateien werden über
	 * $GLOBALS['TL_JAVASCRIPT'] und $GLOBALS['TL_CSS'] in das Seitenlayout
	 * eingehängt.
	 */
	protected function compile()
	{
		$objFile = new File($this->pgn_file);

		// Der Datenbankeintrag kann auf eine inzwischen gelöschte Datei zeigen
		if (!$objFile->exists())
		{
			return;
		}

		$strPieceSet = $this->pgn_pieceset ?: 'merida';

		// Der Viewer holt die Partie über einen eigenen Aufruf. Die Adresse muss
		// deshalb vom Wurzelverzeichnis aus gelten und nicht von der Seite, auf
		// der das Element steht.
		$this->Template->pgnUrl = Environment::get('path') . '/' . System::urlEncode($objFile->path);

		$this->Template->pieceSet = $strPieceSet;
		$this->Template->boardStyle = $this->pgn_boardstyle;

		// Der Viewer bekommt die Kantenlänge des Brettes; ein Feld ist ein
		// Achtel davon, damit die eingestellte Figurengröße erhalten bleibt
		$this->Template->boardSize = (8 * (int) ($this->pgn_piecesize ?: 46)) . 'px';

		$this->Template->coordsStyle = $this->pgn_coordinates ? 'left-bottom' : 'none';
		$this->Template->gameHeader = $this->pgn_gamestat ? 'true' : 'false';
		$this->Template->movePosition = $this->pgn_boardfirst ? 'under' : 'right';
		$this->Template->moveListStyle = $this->pgn_moveformat ? 'twocolumn' : 'indented';
		$this->Template->autoplaySpeed = (int) ($this->pgn_pause ?: 800);
		$this->Template->notationSize = (int) $this->pgn_notationsize;

		// Der Ton lässt sich zusätzlich in den Einstellungen ganz abschalten
		$blnSound = (bool) $this->pgn_sound && (bool) Config::get('pgnviewer_sound');
		$this->Template->disableSound = $blnSound ? 'false' : 'true';

		if ($this->pgn_download)
		{
			$this->addDownloadLink($objFile);
		}

		$this->addAssets();
	}

	/**
	 * Ergänzt das Template um die Angaben für den Downloadlink.
	 *
	 * Der Link zeigt auf die aktuelle Seite und hängt die gewünschte Datei als
	 * Parameter an; ausgeliefert wird sie dann in generate(). Ein bereits
	 * vorhandener Parameter wird vorher entfernt, damit sich die Parameter bei
	 * mehreren Aufrufen nicht aneinanderreihen (siehe #5683).
	 *
	 * @param File $objFile Die eingebundene PGN-Datei
	 */
	private function addDownloadLink(File $objFile): void
	{
		$strLinkTitle = $this->pgn_linkTitle ?: $objFile->basename;
		$strHref = Environment::get('requestUri');

		if (null !== Input::get('file'))
		{
			$strHref = preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
		}

		$strHref .= (strpos($strHref, '?') !== false ? '&amp;' : '?') . 'file=' . System::urlEncode($objFile->value);

		$this->Template->link = $strLinkTitle;
		$this->Template->title = StringUtil::specialchars($this->pgn_titleText ?: $strLinkTitle);
		$this->Template->href = $strHref;
		$this->Template->filesize = System::getReadableSize($objFile->filesize, 1);
		$this->Template->icon = 'bundles/contaopgnviewer/images/iconPGN.gif';
		$this->Template->mime = $objFile->mime;
		$this->Template->extension = $objFile->extension;
		$this->Template->path = $objFile->dirname;
	}

	/**
	 * Bindet die Dateien des Viewers in das Layout ein.
	 *
	 * Die Reihenfolge ist wichtig: ct-setup.js und die Sprachdatei müssen vor
	 * dem Viewer selbst geladen werden, weil sie Werte setzen, die der Viewer
	 * beim Start ausliest. Alle Einträge bekommen einen Schlüssel, damit die
	 * Dateien bei mehreren Brettern auf einer Seite nur einmal im Quelltext
	 * stehen.
	 *
	 * Die Datei zum gewählten Figurensatz holt sich der Viewer selbst aus dem
	 * Bundle, sobald er sie braucht; dafür sorgt der in ct-setup.js gesetzte
	 * Pfad.
	 */
	private function addAssets(): void
	{
		$strBase = 'bundles/contaopgnviewer/';

		$GLOBALS['TL_JAVASCRIPT']['contaopgnviewer_setup'] = $strBase . 'js/ct-setup.js';

		$strLanguage = (string) Config::get('pgnviewer_notationlang');

		if (isset(self::NOTATION_FILES[$strLanguage]))
		{
			$GLOBALS['TL_JAVASCRIPT']['contaopgnviewer_locale'] = $strBase . 'js/locale/' . self::NOTATION_FILES[$strLanguage] . '.js';
		}

		$GLOBALS['TL_JAVASCRIPT']['contaopgnviewer'] = $strBase . 'pgnviewer/pgnviewerext.bundle.vers1.js';

		$GLOBALS['TL_CSS']['contaopgnviewer_vendor'] = $strBase . 'pgnviewer/pgnviewerext.vers1.css';
		$GLOBALS['TL_CSS']['contaopgnviewer'] = $strBase . 'css/pgnviewer.css';
	}

	/**
	 * Prüft, ob die aktuelle Anfrage aus dem Backend kommt.
	 *
	 * Die frühere Konstante TL_MODE gibt es in Contao 5 nicht mehr, deshalb wird
	 * der Scope-Matcher des Kerns befragt. Läuft der Aufruf ohne Request, etwa
	 * auf der Kommandozeile, gilt er nicht als Backend-Anfrage.
	 *
	 * @return bool true, wenn die Anfrage im Backend läuft
	 */
	private function isBackendRequest(): bool
	{
		$container = System::getContainer();

		if (null === $container || !$container->has('request_stack'))
		{
			return false;
		}

		$request = $container->get('request_stack')->getCurrentRequest();

		return null !== $request && $container->get('contao.routing.scope_matcher')->isBackendRequest($request);
	}
}
