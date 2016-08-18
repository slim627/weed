<?php

namespace StaffBundle\Security\EntryPoint;

use CommonBundle\Utils\ResponseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 12.2.16
 * Time: 12.52
 */
class AjaxEntryPoint implements AuthenticationEntryPointInterface
{
    use ResponseTrait;

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return $this->error('Unauthorized', 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
    }
}