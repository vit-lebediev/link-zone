<?php

namespace LinkZone\Core\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if (method_exists($controller[0], 'init')) {
            $controller[0]->init();
        }
    }
}
