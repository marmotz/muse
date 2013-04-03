<?php

namespace Simplex\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleLoader implements EventSubscriberInterface {
    protected $defaultLocale;

    public function __construct($defaultLocale = 'en') {
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::REQUEST => 'onRequest'
        );
    }

    public function onRequest(GetResponseEvent $event) {
        $session = $event->getRequest()->getSession();

        if(!$session->has('_locale')) {
            $session->set('_locale', $this->defaultLocale);
        }
    }
}