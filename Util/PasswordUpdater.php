<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 24/11/17
 * Time: 10:38
 */

namespace Lch\UserBundle\Util;

use Lch\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordUpdater {
	/** @var EncoderFactoryInterface */
	private $encoderFactory;

	/**
	 * PasswordUpdater constructor.
	 *
	 * @param EncoderFactoryInterface $encoderFactory
	 */
	public function __construct( EncoderFactoryInterface $encoderFactory ) {
		$this->encoderFactory = $encoderFactory;
	}

	/**
	 * Hash a user password
	 *
	 * @param User $user
	 *
	 * @throws \Exception
	 */
	public function hashPassword( User $user ) {
		$plainPassword = $user->getPassword();

		if ( 0 === strlen( $plainPassword ) ) {
			return;
		}

		$encoder = $this->encoderFactory->getEncoder( $user );

		$hashedPassword = $encoder->encodePassword( $plainPassword, $user->getSalt() );
		$user->setPassword( $hashedPassword );
	}
}
