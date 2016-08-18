<?php

namespace DashboardBundle\Controller;

use CommonBundle\Annotation\AjaxOnlyRequest;
use StaffBundle\Annotation\KXSecureAction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @AjaxOnlyRequest()
     * @Route("/", name="dashboard")
     */
    public function dashboardAction()
    {
        return $this->render('DashboardBundle:Default:dashboard.html.twig');
    }
}
