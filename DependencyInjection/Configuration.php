<?php

namespace Lch\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
	const ROOT_NODE = 'lch_user';
	const USER_CLASS = 'user_class';
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(static::ROOT_NODE);

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

	    $rootNode
		    ->children()
			    ->scalarNode(static::USER_CLASS)
			        ->info('Defines the user class to be used. It have to extends Lch\UserBundle\Entity\User')
	                ->isRequired()
			        ->cannotBeEmpty()
			    ->end()
		    ->end()
	    ;

        return $treeBuilder;
    }
}
