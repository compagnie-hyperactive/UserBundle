<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 22/11/17
 * Time: 07:17
 */

namespace Lch\UserBundle\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login()
    {
        $error = $this->get('security.authentication_utils')->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->get('security.authentication_utils')->getLastUsername();

        return $this->render('@LchUser/Security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }
}
