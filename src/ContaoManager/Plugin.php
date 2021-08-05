<?php

namespace Schachbulle\ContaoPgnviewerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Schachbulle\ContaoPgnviewerBundle\ContaoPgnviewerBundle;

class Plugin implements BundlePluginInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getBundles(ParserInterface $parser)
	{
		return [
			BundleConfig::create(ContaoPgnviewerBundle::class)
				->setLoadAfter([ContaoCoreBundle::class]),
		];
	}
}
