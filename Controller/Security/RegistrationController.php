<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 14/02/18
 * Time: 11:37
 */

namespace Lch\UserBundle\Controller\Security;

use Lch\UserBundle\Entity\User;
use Lch\UserBundle\Form\Security\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{

	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function register(Request $request)
	{
		$user = $this->get('lch_user.manager')->create();
		$form = $this->createForm(RegistrationType::class, $user);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$password = $this->get('lch_user.manager')->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($password);

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();


			return $this->redirectToRoute('lch_login');
		}

		return $this->render(
			$this->getParameter('lch_user.templates.registration'),
			array('form' => $form->createView())
		);
	}
}