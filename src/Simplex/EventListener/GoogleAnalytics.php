<?php

namespace Simplex\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Simplex\Event;


abstract class GoogleAnalytics implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array('response' => 'onResponse');
    }

    public function onResponse(Event\Response $event)
    {
        $response = $event->getResponse();

        if ($response->isRedirection()
        || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
        || $event->getRequest()->getRequestFormat() !== 'html'
        ) {
            return;
        }

        $response->setContent($response->getContent() . $this->getGoogleAnalyticsCode());
    }

    abstract public function getGoogleAnalyticsCode();
}