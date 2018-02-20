<?php

namespace Lch\UserBundle\DependencyInjection;

use Lch\UserBundle\Manager\UserManager;
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
	const RESETTING_TTL = 'resetting_ttl';

	/**
	 * Classes
	 */
	const CLASSES = 'classes';
	const USER_CLASS = 'user_class';
	const MANAGER_CLASS = 'manager_class';

	/**
	 * Templates
	 */
	const TEMPLATES = 'templates';
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
				->scalarNode(static::RESETTING_TTL)
					->info('Defines the TTL for the resetting link')
					->isRequired()
					->cannotBeEmpty()
				->end()
				->arrayNode(static::CLASSES)
					->children()
						->scalarNode(static::USER_CLASS)
							->info('Defines the user class to be used. It have to extends Lch\UserBundle\Entity\User')
							->isRequired()
							->cannotBeEmpty()
						->end()
						->scalarNode(static::MANAGER_CLASS)
							->info('Defines the user manager class to be used. Use  Lch\UserBundle\Manager\User as an example')
							->isRequired()
							->defaultValue(UserManager::class)
							->cannotBeEmpty()
						->end()
					->end()
				->end()
			->end()
		;

		return $treeBuilder;
	}
}
