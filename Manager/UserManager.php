<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 24/11/17
 * Time: 08:13
 */

namespace Lch\UserBundle\Manager;

use Lch\UserBundle\Entity\User;
use Lch\UserBundle\Util\PasswordUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager {
	/** @var EntityManagerInterface */
	private $em;

	/** @var PasswordUpdater */
	private $passwordUpdater;

	/**
	 * @var string $userClass the user class FQDN
	 */
	private $userClass;

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;


	/**
	 * UserManager constructor.
	 *
	 * @param EntityManagerInterface $em
	 * @param PasswordUpdater $passwordUpdater
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param $userClass string the app class user
	 */
	public function __construct( EntityManagerInterface $em, PasswordUpdater $passwordUpdater, UserPasswordEncoderInterface $passwordEncoder, $userClass ) {
		$this->em              = $em;
		$this->passwordUpdater = $passwordUpdater;
		$this->passwordEncoder = $passwordEncoder;
		$this->userClass       = $userClass;
	}


	/**
	 * @return User
	 */
	public function create() {
		return new $this->userClass();
	}

	/**
	 * Find a user by his username
	 *
	 * @param string $username
	 *
	 * @return AdvancedUserInterface|null
	 */
	public function findUserByUsername( $username ) {
		$user = $this->em->getRepository( $this->userClass )->findOneBy( [ 'username' => $username ] );

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
		$user = $this->em->getRepository( $this->userClass )->findOneBy( [ 'confirmationToken' => $confirmationToken ] );

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
	 * TODO See if this can be removed to be able to use updateUserPassword algo & tools
	 * @param User $user
	 * @param $clearPassword
	 *
	 * @return mixed
	 */
	public function encodePassword(User $user, $clearPassword) {
		return $this->passwordEncoder->encodePassword($user, $clearPassword);
	}
}
