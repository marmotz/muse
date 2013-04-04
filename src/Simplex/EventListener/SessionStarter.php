<?php

namespace Simplex\EventListener;

use Simplex\Session\Secure;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
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
                new Session(
                    new NativeSessionStorage(
                        array(),
                        new Secure
                    )
                )
            );
        }
    }
}