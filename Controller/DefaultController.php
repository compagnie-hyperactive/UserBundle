<?php

namespace Lch\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LchUserBundle:Default:index.html.twig');
    }
}
