<?php

namespace Simplex\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Simplex\Event;


class ContentLength implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array('response' => 'onResponse');
    }

    public function onResponse(Event\Response $event)
    {
        $response = $event->getResponse();
        $headers = $response->headers;

        if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
            $headers->set('Content-Length', strlen($response->getContent()));
        }
    }
}