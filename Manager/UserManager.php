<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 24/11/17
 * Time: 08:13
 */

namespace Lch\UserBundle\Manager;

use Lch\UserBundle\DependencyInjection\Configuration;
use Lch\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager {
	/** @var EntityManagerInterface */
	private $em;

	/** @var PasswordManager */
	private $passwordManager;

	/**
	 * @var array $classes the bundle classes
	 */
	private $classes;

	/**
	 * UserManager constructor.
	 *
	 * @param EntityManagerInterface $em
	 * @param PasswordManager $passwordManager
	 * @param $classes array bundle classes
	 */
	public function __construct(
		EntityManagerInterface $em,
		PasswordManager $passwordManager,
		$classes
	) {
		$this->em              = $em;
		$this->passwordManager = $passwordManager;
		$this->classes         = $classes;
	}


	/**
	 * @return User
	 */
	public function create() {
		return new $this->classes[Configuration::USER];
	}

	/**
	 * Find a user by his username
	 *
	 * @param string $username
	 *
	 * @return AdvancedUserInterface|null
	 */
	public function findUserByUsername( $username ) {
		$user = $this->em->getRepository( $this->classes[Configuration::USER] )->findOneBy( [ 'username' => $username ] );

		return $user;
	}

	/**
	 * Update a user
	 *
	 * @param AdvancedUserInterface $user
	 *
	 * @param bool $andFlush
	 */
	public function updateUser( AdvancedUserInterface $user, $andFlush = true ) {
		$this->em->persist( $user );

		if ( $andFlush ) {
			$this->em->flush();
		}
	}

	/**
	 * Find a user by his confirmationToken
	 *
	 * @param $confirmationToken
	 *
	 * @return AdvancedUserInterface|null
	 */
	public function findUserByConfirmationToken( $confirmationToken ) {
		$user = $this->em->getRepository( $this->classes[Configuration::USER] )->findOneBy( [ 'confirmationToken' => $confirmationToken ] );

		return $user;
	}

	/**
	 * Encode & update user password
	 *
	 * @param User $user
	 *
	 * @throws \Exception
	 */
	public function updateUserPassword( User $user ) {
		$this->passwordUpdater->hashPassword( $user );
		$user->setConfirmationToken( null );
		$user->setPasswordRequestedAt( null );
	}

	
	/**
	 * @param User $user
	 */
	public function register(User $user) {
		$password = $this->passwordManager->encodePassword($user, $user->getPlainPassword());
		$user->setPassword($password);

		$this->em->persist($user);
		$this->em->flush();

		// TODO add event after registration
	}
}
