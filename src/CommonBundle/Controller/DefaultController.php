<?php

namespace CommonBundle\Controller;

use CommonBundle\Utils\ResponseTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    use ResponseTrait;

    /**
     * Return base layout and initialize AngularJS
     *
     * @Route("/", name="base_layout")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('CommonBundle:Default:index.html.twig');
    }
}
