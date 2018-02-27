<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 24/11/17
 * Time: 10:13
 */

namespace Lch\UserBundle\Util;

use Lch\UserBundle\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Mailer {

	/** @var \Twig_Environment */
	private $twig;

	/** @var \Swift_Mailer */
	private $swift;

	/**
	 * @var string
	 */
	private $mailTemplate;

	public function __construct( \Twig_Environment $twig, \Swift_Mailer $mailer, $mailTemplate ) {
		$this->twig   = $twig;
		$this->swift  = $mailer;
		$this->mailTemplate = $mailTemplate;
	}

	/**
	 * @param \Lch\UserBundle\Entity\User $user
	 * @param $resetRoute
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function sendResetPasswordEmail( User $user, $resetRoute ) {
		$message = ( new \Swift_Message( 'Réinitialisation de votre mot de passe' ) )
			->setFrom( 'mama003@gmail.com' )
			->setTo( $user->getEmail() )
			->setBody(
				$this->twig->render(
					$this->mailTemplate, [
						'user' => $user,
						'url'  => $resetRoute,
					]
				),
				'text/html'
			);

		$this->swift->send( $message );
	}
}
