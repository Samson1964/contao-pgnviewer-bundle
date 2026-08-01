<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-pgnviewer-bundle.
 *
 * (c) Wilfried Krebbers, Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoPgnviewerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Schachbulle\ContaoPgnviewerBundle\ContaoPgnviewerBundle;

/**
 * Registriert das Bundle im Contao Manager.
 */
class Plugin implements BundlePluginInterface
{
	/**
	 * Meldet das Bundle beim Kernel an.
	 *
	 * Das Bundle wird nach dem Contao-Kern geladen, damit dessen DCA-Dateien
	 * bereits vorliegen, wenn die Palette des Inhaltselements ergänzt wird.
	 *
	 * @param ParserInterface $parser Wird vom Contao Manager übergeben und hier
	 *                                nicht benötigt, weil keine externe
	 *                                Konfigurationsdatei eingelesen wird
	 *
	 * @return array<BundleConfig> Die Konfiguration des Bundles
	 */
	public function getBundles(ParserInterface $parser)
	{
		return array(
			BundleConfig::create(ContaoPgnviewerBundle::class)
				->setLoadAfter(array(ContaoCoreBundle::class)),
		);
	}
}
