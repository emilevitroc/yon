<?php

namespace Yon\Bundle\ParisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('YonParisBundle:Default:index.html.twig');
    }
}
