<?php

namespace Lch\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LchUserBundle:Default:index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
//        $request = $this->getRequest();
        $session = $request->getSession();

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        $args = array(
            "last_username" => $session->get(Security::LAST_USERNAME),
            "error"         => $error,
        );

        return $this->render('@LchUser/Default/login.html.twig', $args);
    }

    /**
     *
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     *
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
