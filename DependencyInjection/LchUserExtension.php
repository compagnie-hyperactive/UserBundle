<?php

namespace Lch\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class LchUserExtension extends Extension
{
	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);

		// Make class mapping with parameters for further use (DI)
		array_map(function($fqdnClass, $key) use ($container){
			$container->setParameter(
				Configuration::ROOT_NODE . '.' . Configuration::CLASSES . '.' . $key,
				$fqdnClass
			);
		}, $config[Configuration::CLASSES], array_keys($config[Configuration::CLASSES]));


		// Make templates mapping with parameters for further use (DI)
		array_map(function($templatePath, $key) use ($container){
			$container->setParameter(
				Configuration::ROOT_NODE . '.' . Configuration::TEMPLATES . '.' . $key,
				$templatePath
			);
		}, $config[Configuration::TEMPLATES], array_keys($config[Configuration::TEMPLATES]));

		// Make types mapping with parameters for further use (DI)
		array_map(function($formClass, $key) use ($container){
			$container->setParameter(
				Configuration::ROOT_NODE . '.' . Configuration::FORMS . '.' . $key,
				$formClass
			);
		}, $config[Configuration::FORMS], array_keys($config[Configuration::FORMS]));



		// Set Resetting TTL as parameter
		$container->setParameter(
			Configuration::ROOT_NODE . '.' . Configuration::RESETTING_TTL,
			$config[Configuration::RESETTING_TTL]
		);

		//TODO Check provided classes matching Interface instances


		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.yml');
	}
}
