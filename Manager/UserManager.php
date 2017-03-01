<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 01/03/17
 * Time: 16:12
 */

namespace Lch\UserBundle\Manager;


use Doctrine\ORM\EntityManager;
use Lch\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{

    /**
     * @var EntityManager
     */
    private  $entityManager;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * UserManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, EncoderFactoryInterface $encoderFactory) {

        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
    }

    public function create($firstName, $lastName, $username, $email, $plainPassword) {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($plainPassword, $user->getSalt()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}