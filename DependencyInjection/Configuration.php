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
	const REGISTRATION = 'registration';
	const CHECK_EMAIL = 'check_email';
	const RESET_PASSWORD = 'reset_password';

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
							->info('Defines the login template. Use @LchUser/login.html.twig as inspiration')
							->cannotBeEmpty()
							->defaultValue('@LchUser/login.html.twig')
						->end()
						->scalarNode(static::REGISTRATION)
							->info('Defines the registration template. Use @LchUser/registration/register.html.twig as inspiration')
							->cannotBeEmpty()
							->defaultValue('@LchUser/registration/register.html.twig')
						->end()
						->scalarNode(static::CHECK_EMAIL)
							->info('Defines the check email template. Use @LchUser/check-email.html.twig as inspiration')
							->cannotBeEmpty()
							->defaultValue('@LchUser/check-email.html.twig')
						->end()
						->scalarNode(static::RESET_PASSWORD)
							->info('Defines the reset password template. Use @LchUser/reset-password.html.twig as inspiration')
							->cannotBeEmpty()
							->defaultValue('@LchUser/reset-password.html.twig')
						->end()
					->end()
				->end()
			->end()
		;

		return $treeBuilder;
	}
}
