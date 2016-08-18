<?php

namespace CommonBundle\EventListener;

use CommonBundle\Annotation\AjaxOnlyRequest;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class AjaxRequestListener
 *
 * @package CommonBundle\EventListener
 * @author Samusevich Alexander
 */
class AjaxRequestListener
{
    private $reader;

    /**
     * AjaxRequestListener constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * This event will fire during any controller call
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $method = new \ReflectionMethod($controller[0], $controller[1]);

        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof AjaxOnlyRequest) {
                $annotation->execute($event);
            }
        }
    }
}