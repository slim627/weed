<?php

namespace StaffBundle\Security;

use CommonBundle\Utils\ResponseTrait;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 12.2.16
 * Time: 12.36
 */
class AuthenticationHandler implements
    AuthenticationSuccessHandlerInterface,
    AuthenticationFailureHandlerInterface
{
    use ResponseTrait;
    use ContainerAwareTrait;

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $employee = $token->getUser();
        $notifications = $this->container->get('doctrine')
            ->getManager()
            ->getRepository('StaffBundle:Notification')
            ->findBy(['employee' => $employee, 'isViewed' => false]);

        return $this->response([
            'user' => $employee,
            'notifications' => $notifications,
        ]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return $this->error($exception->getMessage(), $exception->getTraceAsString(), null, Response::HTTP_FORBIDDEN);
    }
}