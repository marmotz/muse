<?php

namespace Simplex\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class StringResponse implements EventSubscriberInterface {
    public static function getSubscribedEvents() {
        return array(
            KernelEvents::VIEW => 'onView'
        );
    }

    public function onView(GetResponseForControllerResultEvent $event) {
        $response = $event->getControllerResult();

        if (is_string($response)) {
            $event->setResponse(new Response($response));
        }
    }
}