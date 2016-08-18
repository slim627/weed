<?php

namespace Application\FOS\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApplicationFOSUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
