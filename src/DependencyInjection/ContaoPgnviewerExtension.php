<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-pgnviewer-bundle.
 *
 * (c) Wilfried Krebbers, Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoPgnviewerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Lädt die Service-Konfiguration des Bundles in den Container.
 */
class ContaoPgnviewerExtension extends Extension
{
	/**
	 * Bindet die services.yaml des Bundles ein.
	 *
	 * @param array<mixed>     $mergedConfig Die zusammengeführte Konfiguration
	 *                                       des Bundles; das Bundle wertet keine
	 *                                       eigenen Konfigurationsschlüssel aus
	 * @param ContainerBuilder $container    Der Container, in den die Dienste
	 *                                       eingetragen werden
	 */
	public function load(array $mergedConfig, ContainerBuilder $container): void
	{
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__ . '/../Resources/config')
		);

		$loader->load('services.yaml');
	}
}
