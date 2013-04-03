<?php

namespace Simplex\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Simplex\Event;


class ContentLength implements EventSubscriberInterface {
    public static function getSubscribedEvents() {
        return array(
            KernelEvents::VIEW => 'onView'
        );
    }

    public function onView(GetResponseForControllerResultEvent $event) {
        $response = $event->getControllerResult();

        if($response instanceof Response) {
            $headers = $response->headers;

            if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
                $headers->set('Content-Length', strlen($response->getContent()));
            }
        }
    }
}