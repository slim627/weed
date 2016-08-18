<?php

namespace APIBundle\Controller;

use CommonBundle\Utils\ResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    use ResponseTrait;

    /**
     * @Route("/", name="api.hello")
     * @return JsonResponse
     */
    public function indexAction()
    {
        return $this->response('Hello, I`am KX Json API');
    }
}
