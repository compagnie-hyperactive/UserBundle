<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 24/11/17
 * Time: 07:40
 */

namespace Lch\UserBundle\Controller\Security;

use Lch\UserBundle\Entity\User;
use Lch\UserBundle\Form\Type\Security\ResetPasswordType;
use Lch\UserBundle\Manager\UserManager;
use Lch\UserBundle\Util\Mailer;
use Lch\UserBundle\Util\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResettingController extends Controller
{

	/**
	 * Step 1: Display a form to ask username
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function requestPassword()
	{
		return $this->render('@LchUser/Security/request-password.html.twig');
	}

	/**
	 * Step 2: Send a resetting password email to user
	 *
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function sendEmail(Request $request)
	{
		$username = $request->request->get('username');
		$userManager = $this->get('lch_user_manager');
		$mailer = $this->get('lch_user_mailer');
		$tokenGenerator = $this->get('lch_user_token_generator');

		/** @var User $user */
		$user = $userManager->findUserByUsername($username);

		if (null !== $user && !$user->isPasswordRequestNonExpired($this->getParameter('lch_user.resetting.ttl'))) {

			if (null === $user->getConfirmationToken()) {
				$user->setConfirmationToken($tokenGenerator->generateToken());
			}

			// Send Email
			$mailer->sendResetPasswordEmail($user);

			$user->setPasswordRequestedAt(new \DateTime());
			$userManager->updateUser($user);
		}

		return new RedirectResponse($this->generateUrl('lch_check_email', ['username' => $username]));
	}

	/**
	 * Step 3: Inform user that an email has been sent
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function checkEmail(Request $request)
	{
		$username = $request->query->get('username');

		if (empty($username)) {
			// the user does not come from the sendEmail action
			return new RedirectResponse($this->generateUrl('fos_user_resetting_request'));
		}

		return $this->render('@LchUser/Security/check-email.html.twig', array(
			'tokenLifetime' => ceil($this->getParameter('lch_user.resetting.ttl') / 3600),
		));
	}

	/**
	 * Step 4: Display a form for choose a new password
	 *
	 * @param Request $request
	 * @param $token
	 *
	 * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function reset(Request $request, $token)
	{
		$userManager = $this->get('lch_user_manager');

		$user = $userManager->findUserByConfirmationToken($token);

		if (null === $user) {
			throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
		}

		$form = $this->createForm(ResetPasswordType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$userManager->updateUserPassword($user);
			$userManager->updateUser($user);

			return $this->redirectToRoute('lch_login');
		}

		return $this->render('@LchUser/Security/reset-password.html.twig', [
			'form' => $form->createView(),
		]);
	}
}
