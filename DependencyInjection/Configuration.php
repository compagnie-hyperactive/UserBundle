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
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lch_user');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

	    $rootNode
		    ->scalarNode('user_class')
		        ->info('Defines the user class to be used. It have to extends Lch\UserBundle\Entity\User')
                ->isRequired()
		        ->cannotBeEmpty()
		    ->end()
	    ;

        return $treeBuilder;
    }
}
