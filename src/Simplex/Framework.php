<?php

namespace Simplex;

use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Framework extends HttpKernel\HttpKernel
{
    public function __construct(Routing\RouteCollection $routes)
    {
        $context  = new Routing\RequestContext();
        $matcher  = new Routing\Matcher\UrlMatcher($routes, $context);
        $resolver = new HttpKernel\Controller\ControllerResolver();

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher));
        $dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
        $dispatcher->addSubscriber(new EventListener\ContentLength());
        $dispatcher->addSubscriber(new EventListener\ArrayResponse($routes, $context));
        $dispatcher->addSubscriber(new EventListener\StringResponse());

        parent::__construct($dispatcher, $resolver);
    }


    public function addEventDispatcherSubscriber(EventSubscriberInterface $subscriber) {
        $this->dispatcher->addSubscriber($subscriber);
    }
}