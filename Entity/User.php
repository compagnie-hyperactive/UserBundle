<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 21/11/17
 * Time: 19:18
 */

namespace Lch\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
// TODO extract all mapping informations to be ORM agnostic
/**
 * @ORM\MappedSuperclass()
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
abstract class User implements AdvancedUserInterface, \Serializable
{
    const ROLE_DEFAULT = 'ROLE_USER';

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=25, unique=true)
	 */
	protected $username;


	/**
	 * @ORM\Column(type="string", length=100, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	protected $email;

	/**
	 * @Assert\Length(max=4096)
	 */
	protected $plainPassword;

	/**
	 * @ORM\Column(type="string", length=64)
	 */
	protected $password;

	/**
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	protected $isActive;

	/**
	 * Random string sent to the user email address in order to verify it.
	 *
	 * @var string
	 *
	 * @ORM\Column(type="string", length=180, nullable=true)
	 */
	protected $confirmationToken;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $passwordRequestedAt;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $roles;

    /**
     * @param $role
     * @return $this
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
        return $this;
    }

	/**
	 * User constructor.
	 */
	public function __construct() {
		$this->isActive = true;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param $username
	 *
	 * @return $this
	 */
	public function setUsername( $username ) {
		$this->username = $username;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUsername() {
		return $this->username;
	}

	public function getPlainPassword() {
		return $this->plainPassword;
	}

	public function setPlainPassword( $password ) {
		$this->plainPassword = $password;
	}

	public function getSalt() {
		return null;
	}

	public function setSalt() {
		return null;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param mixed $email
	 *
	 * @return User
	 */
	public function setEmail( $email ) {
		$this->email    = $email;
		$this->username = $email;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param mixed $password
	 *
	 * @return User
	 */
	public function setPassword( $password ) {
		$this->password = $password;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function isActive() {
		return $this->isActive;
	}

	/**
	 * @param mixed $isActive
	 *
	 * @return User
	 */
	public function setActive( $isActive ) {
		$this->isActive = $isActive;

		return $this;
	}

	public function eraseCredentials() {
	}

	public function isAccountNonExpired() {
		return true;
	}

	public function isAccountNonLocked() {
		return true;
	}

	public function isCredentialsNonExpired() {
		return true;
	}

	public function isEnabled() {
		return $this->isActive;
	}

	/**
	 * @return string|null
	 */
	public function getConfirmationToken() {
		return $this->confirmationToken;
	}

	/**
	 * @param $confirmationToken
	 *
	 * @return $this
	 */
	public function setConfirmationToken( $confirmationToken ) {
		$this->confirmationToken = $confirmationToken;

		return $this;
	}

	/**
	 * @param \DateTime|null $date
	 *
	 * @return $this
	 */
	public function setPasswordRequestedAt( \DateTime $date = null ) {
		$this->passwordRequestedAt = $date;

		return $this;
	}

	/**
	 * Gets the timestamp that the user requested a password reset.
	 *
	 * @return null|\DateTime
	 */
	public function getPasswordRequestedAt() {
		return $this->passwordRequestedAt;
	}

	/**
	 * @param $ttl
	 *
	 * @return bool
	 */
	public function isPasswordRequestNonExpired( $ttl ) {
		return $this->getPasswordRequestedAt() instanceof \DateTime &&
		       $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
	}

	/** @see \Serializable::serialize() */
	public function serialize() {
		return serialize( array(
			$this->id,
			$this->email,
			$this->username,
			$this->password,
			$this->isActive,
		) );
	}

	/**
	 * @see \Serializable::unserialize()
	 *
	 * @param string $serialized
	 *
	 * @return array
	 */
	public function unserialize( $serialized ) {
		return list (
			$this->id,
			$this->email,
			$this->username,
			$this->password,
			$this->isActive,
			) = unserialize( $serialized );
	}


    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        return $this;
    }
}
