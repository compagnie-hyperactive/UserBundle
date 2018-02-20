<?php

namespace Lch\UserBundle\DependencyInjection;

use Lch\UserBundle\Manager\UserManager;
use Lch\UserBundle\Util\Mailer;
use Lch\UserBundle\Util\TokenGenerator;
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
	const USER = 'user';
	const MANAGER = 'manager';
	const MAILER = 'mailer';
	const TOKEN_GENERATOR = 'token_generator';

	/**
	 * Templates
	 */
	const TEMPLATES = 'templates';
	const LOGIN = 'login';

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
						->scalarNode(static::USER)
							->info('Defines the user class to be used. It have to extends Lch\UserBundle\Entity\User')
							->isRequired()
							->cannotBeEmpty()
						->end()
						->scalarNode(static::MANAGER)
							->info('Defines the user manager class to be used. Use Lch\UserBundle\Manager\User as an example')
							->defaultValue(UserManager::class)
							->cannotBeEmpty()
						->end()
						->scalarNode(static::MAILER)
							->info('Defines the mailer class to be used. Use Lch\UserBundle\Util\Mailer as an example')
							->defaultValue(Mailer::class)
							->cannotBeEmpty()
						->end()
						->scalarNode(static::TOKEN_GENERATOR)
							->info('Defines the token generation class to be used. Use Lch\UserBundle\Util\TokenGenerator as an example')
							->defaultValue(TokenGenerator::class)
							->cannotBeEmpty()
						->end()
					->end()
				->end()
				->arrayNode(static::TEMPLATES)
					->canBeEnabled()
					->children()
						->scalarNode(static::LOGIN)
							->info('Defines the login template. Use @LchUser/Security/login.html.twig as inspiration')
							->cannotBeEmpty()
							->defaultValue('@LchUser/Security/login.html.twig')
						->end()
					->end()
				->end()
			->end()
		;

		return $treeBuilder;
	}
}
