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
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Environment;
use Contao\File;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;
use Contao\Template;
use Symfony\Component\HttpFoundation\RequestStack;

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
 *
 * Die Felder aus der DCA sind keine echten Eigenschaften: Contao reicht sie
 * über __get und __set der Elternklasse durch. Die folgenden Angaben sagen
 * Entwicklungsumgebung und statischer Analyse, welche es gibt und welchen Typ
 * sie haben.
 *
 * @property string|null    $pgn_file        Binäre UUID der PGN-Datei; in generate() durch den Pfad ersetzt
 * @property string         $pgn_pieceset    Figurensatz, etwa „merida“
 * @property string|integer $pgn_piecesize   Kantenlänge einer Figur in Pixel
 * @property string         $pgn_boardstyle  Brettstil, etwa „wood-dark“; leer heißt Voreinstellung des Viewers
 * @property string|boolean $pgn_coordinates Koordinaten am Brettrand anzeigen
 * @property string|boolean $pgn_gamestat    Kopfzeile mit den Partiedaten anzeigen
 * @property string         $pgn_boardfirst  Anordnung: leer = Zugliste rechts, „1“ = darunter, „wrap“ = umfließend
 * @property string|boolean $pgn_moveformat  Zugliste zweispaltig statt eingerückt
 * @property string|integer $pgn_notationsize Höhe der Zugliste in Pixel, 0 für unbeschränkt
 * @property string|integer $pgn_pause       Pause zwischen den Zügen in Millisekunden
 * @property string|boolean $pgn_sound       Züge akustisch begleiten
 * @property string|boolean $pgn_backlink    Lizenzhinweis auf Chesstempo anzeigen
 * @property string|boolean $pgn_download    Downloadlink der PGN-Datei anbieten
 * @property string         $pgn_linkTitle   Beschriftung des Downloadlinks
 * @property string         $pgn_titleText   title-Attribut des Downloadlinks
 *
 * @property FrontendTemplate $Template Wird von der Elternklasse in generate() angelegt
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

			// setData statt einzelner Zuweisungen: Die Felder eines Templates
			// sind magische Eigenschaften, die kein Werkzeug prüfen kann
			$objTemplate->setData(array
			(
				'wildcard' => '### ' . ($GLOBALS['TL_LANG']['CTE']['pgnviewer'][0] ?? 'PGNVIEWER') . ' ###',
				'title' => $this->headline,
				'id' => $this->id,
			));

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
	protected function compile(): void
	{
		// generate() hat den Wert an dieser Stelle bereits von der UUID auf den
		// Dateipfad umgestellt
		$objFile = new File((string) $this->pgn_file);

		// Der Datenbankeintrag kann auf eine inzwischen gelöschte Datei zeigen
		if (!$objFile->exists())
		{
			return;
		}

		// Der Ton lässt sich zusätzlich in den Einstellungen ganz abschalten
		$blnSound = (bool) $this->pgn_sound && (bool) Config::get('pgnviewer_sound');

		$arrData = array
		(
			// Der Viewer holt die Partie über einen eigenen Aufruf. Die Adresse
			// muss deshalb vom Wurzelverzeichnis aus gelten und nicht von der
			// Seite, auf der das Element steht.
			'pgnUrl' => Environment::get('path') . '/' . System::urlEncode($objFile->path),
			'pieceSet' => $this->pgn_pieceset ?: 'merida',
			'boardStyle' => $this->pgn_boardstyle,

			// Der Viewer bekommt die Kantenlänge des Brettes; ein Feld ist ein
			// Achtel davon, damit die eingestellte Figurengröße erhalten bleibt
			'boardSize' => (8 * (int) ($this->pgn_piecesize ?: 46)) . 'px',

			'coordsStyle' => $this->pgn_coordinates ? 'left-bottom' : 'none',
			'gameHeader' => $this->pgn_gamestat ? 'true' : 'false',
			'moveListStyle' => $this->pgn_moveformat ? 'twocolumn' : 'indented',
			'autoplaySpeed' => (int) ($this->pgn_pause ?: 800),
			'disableSound' => $blnSound ? 'false' : 'true',
		);

		$arrData = array_merge($arrData, $this->getLayoutData());

		if ($this->pgn_download)
		{
			$arrData = array_merge($arrData, $this->getDownloadData($objFile));
		}

		// Die Werte in einem Rutsch übergeben statt einzeln zuzuweisen: Die
		// Variablen eines Templates sind magische Eigenschaften, die kein
		// Werkzeug prüfen kann. Der vorhandene Bestand (Überschrift, CSS-ID,
		// die Felder des Elements) bleibt dabei erhalten.
		$this->Template->setData(array_merge($this->Template->getData(), $arrData));

		$this->addAssets();
	}

	/**
	 * Bestimmt die Anordnung von Brett und Zugliste.
	 *
	 * Drei Möglichkeiten, gesteuert über das Feld pgn_boardfirst:
	 *
	 * - leer:   Zugliste rechts neben dem Brett, beide nebeneinander
	 * - „1“:    Zugliste unter dem Brett (der alte Wert des früheren Kästchens)
	 * - „wrap“: Das Brett steht rechts oben und die Zugliste fließt darum herum.
	 *           Dafür bekommt der Behälter eine eigene Klasse, denn der
	 *           Betrachter kennt diese Anordnung nicht — sie entsteht in
	 *           css/pgnviewer.css. Die Bedienknöpfe wandern über die Zugliste,
	 *           damit sie beim Durchklicken einer langen Partie in der Nähe des
	 *           Brettes bleiben, und die Höhe der Zugliste entfällt: Beim
	 *           Umfließen darf die Liste keinen eigenen Bildlauf haben, sonst
	 *           bliebe sie ein Kasten neben dem Brett, statt es zu umfließen.
	 *
	 * @return array<string, string|integer> Die Angaben zur Anordnung für das
	 *                                       Template
	 */
	private function getLayoutData(): array
	{
		if ('wrap' === $this->pgn_boardfirst)
		{
			return array
			(
				'movePosition' => 'right',
				'buttonsAboveMoves' => 'true',
				'notationSize' => 0,
				'layoutClass' => 'pgn_umfliessend',
			);
		}

		return array
		(
			'movePosition' => $this->pgn_boardfirst ? 'under' : 'right',
			'buttonsAboveMoves' => 'false',
			'notationSize' => (int) $this->pgn_notationsize,
			'layoutClass' => '',
		);
	}

	/**
	 * Stellt die Angaben für den Downloadlink zusammen.
	 *
	 * Der Link zeigt auf die aktuelle Seite und hängt die gewünschte Datei als
	 * Parameter an; ausgeliefert wird sie dann in generate(). Ein bereits
	 * vorhandener Parameter wird vorher entfernt, damit sich die Parameter bei
	 * mehreren Aufrufen nicht aneinanderreihen (siehe #5683).
	 *
	 * @param File $objFile Die eingebundene PGN-Datei
	 *
	 * @return array<string, string> Die Variablen für den Downloadbereich des
	 *                               Templates
	 */
	private function getDownloadData(File $objFile): array
	{
		$strLinkTitle = $this->pgn_linkTitle ?: $objFile->basename;
		$strHref = (string) Environment::get('requestUri');

		if (null !== Input::get('file'))
		{
			$strHref = (string) preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
		}

		$strHref .= (str_contains($strHref, '?') ? '&amp;' : '?') . 'file=' . System::urlEncode($objFile->value);

		return array
		(
			'link' => $strLinkTitle,
			'title' => StringUtil::specialchars($this->pgn_titleText ?: $strLinkTitle),
			'href' => $strHref,
			'filesize' => System::getReadableSize($objFile->filesize, 1),
			'icon' => 'bundles/contaopgnviewer/images/iconPGN.gif',
			'mime' => $objFile->mime,
			'extension' => $objFile->extension,
			'path' => $objFile->dirname,
		);
	}

	/**
	 * Bindet die Dateien des Viewers in das Layout ein.
	 *
	 * Die Stylesheets kommen über TL_CSS in den Seitenkopf, die Skripte dagegen
	 * ans Ende des Body. Das ist keine Geschmacksfrage: Der Viewer sucht beim
	 * Start die <ct-pgn-viewer>-Elemente im Dokument. Steht er im Seitenkopf,
	 * läuft er, bevor es den Body überhaupt gibt, bricht mit „document body not
	 * defined“ ab und lässt eine leere Hülle stehen (siehe BUGREPORT.md).
	 *
	 * Alles aus TL_JAVASCRIPT gibt Contao im Seitenkopf aus. Das Flag „|defer“
	 * würde das Problem lösen, aber nur in Contao 5 — in Contao 4.13 kennt
	 * StringUtil::resolveFlaggedUrl() es nicht und deutet es als Media-Angabe.
	 * TL_BODY gibt es in beiden Fassungen, deshalb dieser Weg.
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

		$this->addBodyScript('contaopgnviewer_setup', $strBase . 'js/ct-setup.js');

		$strLanguage = (string) Config::get('pgnviewer_notationlang');

		if (isset(self::NOTATION_FILES[$strLanguage]))
		{
			$this->addBodyScript('contaopgnviewer_locale', $strBase . 'js/locale/' . self::NOTATION_FILES[$strLanguage] . '.js');
		}

		$this->addBodyScript('contaopgnviewer', $strBase . 'pgnviewer/pgnviewerext.bundle.vers1.js');

		$GLOBALS['TL_CSS']['contaopgnviewer_vendor'] = $strBase . 'pgnviewer/pgnviewerext.vers1.css';
		$GLOBALS['TL_CSS']['contaopgnviewer'] = $strBase . 'css/pgnviewer.css';
	}

	/**
	 * Trägt ein Skript ans Ende des Body ein.
	 *
	 * Der fertige script-Tag wird vom Kern erzeugt, damit die Datei dieselbe
	 * Behandlung erfährt wie jede andere: Der Aufruf hängt bei Bedarf die
	 * Adresse des Asset-Servers davor und ergänzt aus dem Änderungsdatum eine
	 * Fassungsnummer, damit Browser eine neue Fassung auch wirklich holen.
	 *
	 * @param string $strKey  Schlüssel im Array TL_BODY; verhindert, dass die
	 *                        Datei bei mehreren Brettern auf einer Seite
	 *                        mehrfach im Quelltext steht
	 * @param string $strPath Pfad zur Datei, vom Wurzelverzeichnis aus
	 */
	private function addBodyScript(string $strKey, string $strPath): void
	{
		$GLOBALS['TL_BODY'][$strKey] = Template::generateScriptTag(self::addAssetsUrlTo($strPath), false, null);
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
		$objRequestStack = $container->has('request_stack') ? $container->get('request_stack') : null;
		$objScopeMatcher = $container->has('contao.routing.scope_matcher') ? $container->get('contao.routing.scope_matcher') : null;

		// Der Container gibt die Dienste nur als object zurück; die Prüfung sagt
		// sowohl der statischen Analyse als auch dem Programmablauf, womit wir
		// es zu tun haben
		if (!$objRequestStack instanceof RequestStack || !$objScopeMatcher instanceof ScopeMatcher)
		{
			return false;
		}

		$objRequest = $objRequestStack->getCurrentRequest();

		return null !== $objRequest && $objScopeMatcher->isBackendRequest($objRequest);
	}
}
