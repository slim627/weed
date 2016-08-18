<?php

namespace StaffBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use StaffBundle\Annotation\KXSecureAction;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class KXSecureListener
 *
 * @package CommonBundle\EventListener
 * @author Samusevich Alexander
 */
class KXSecureListener
{
    private $reader;
    private $container;

    /**
     * AjaxRequestListener constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader, ContainerInterface $container)
    {
        $this->reader = $reader;
        $this->container = $container;
    }

    /**
     * This event will fire during any controller call
     *
     * @param FilterControllerEvent $event
     * @throws AccessDeniedException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $checker = $this->container->get('security.authorization_checker');

        $method = new \ReflectionMethod($controller[0], $controller[1]);

        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof KXSecureAction) {
                if(!$checker->isGranted($annotation->getRole())){
                    throw new AccessDeniedHttpException();
                }
            }
        }
    }
}