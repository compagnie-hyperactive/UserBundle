<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 14/02/18
 * Time: 11:37
 */

namespace Lch\UserBundle\Controller\Security;

use Lch\UserBundle\Entity\User;
use Lch\UserBundle\Form\Type\Security\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
	    $user = $this->get('lch_user_manager')->create();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            return $this->redirectToRoute('login');
        }

        return $this->render(
            '@LchUser/Security/registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}