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

        // TODO Check provided user class is instance of User

	    // Set user class as parameter to be used for service dependency injection
	    $container->setParameter(
	    	Configuration::ROOT_NODE . '.' . Configuration::CLASSES . '.' . Configuration::USER_CLASS,
		    $config[Configuration::CLASSES][Configuration::USER_CLASS]
	    );

	    // Set Resetting TTL as parameter
	    $container->setParameter(
	    	Configuration::ROOT_NODE . '.' . Configuration::RESETTING_TTL,
		    $config[Configuration::RESETTING_TTL]
	    );

	    // Defines classes parameters


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
