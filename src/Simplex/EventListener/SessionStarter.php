<?php

namespace Simplex\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SessionStarter implements EventSubscriberInterface {
    public static function getSubscribedEvents() {
        return array(
            KernelEvents::REQUEST => 'onRequest'
        );
    }

    public function onRequest(GetResponseEvent $event) {
        $request = $event->getRequest();

        if($request->getSession() === null) {
            $request->setSession(
                new Session
            );
        }
    }
}