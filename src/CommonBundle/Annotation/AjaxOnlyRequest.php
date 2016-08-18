<?php

namespace CommonBundle\Annotation;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class AjaxOnlyRequest
 *
 * @Annotation
 * @Target("METHOD")
 * @package CommonBundle\Annotation
 * @author Samusevich Alexander
 */
final class AjaxOnlyRequest
{
    private $rewriteRoute = 'base_layout';
    private $rewriteRouteParams = [];

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->rewriteRoute = $values['value'];
        }
        if (isset($values['params'])) {
            $this->rewriteRouteParams = $values['params'];
        }
    }

    /**
     * @return string
     */
    public function getRewriteRoute()
    {
        return $this->rewriteRoute;
    }

    /**
     * @return array
     */
    public function getRewriteRouteParams()
    {
        return $this->rewriteRouteParams;
    }

    /**
     * Check request type and if not ajax request detected then redirect to rewriteRoute
     *
     * @param FilterControllerEvent $event
     */
    public function execute(FilterControllerEvent $event)
    {
        if (!$event->getRequest()->isXmlHttpRequest()) {
            $url = $event->getController()[0]->generateUrl($this->rewriteRoute, $this->rewriteRouteParams);
            $response = $event->getController()[0]->redirect($url);

            $event->setController(function() use ($response) {
                return $response;
            });
        }
    }
}