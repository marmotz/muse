<?php

namespace Simplex\EventListener;

use Simplex\Controller\UrlGeneratorInjectable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class UrlGeneratorInjector implements EventSubscriberInterface {
    protected $routes;
    protected $context;


    public function __construct(RouteCollection $routes, RequestContext $context) {
        $this->routes  = $routes;
        $this->context = $context;
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::CONTROLLER => 'onController'
        );
    }

    public function onController(FilterControllerEvent $event) {
        list($controller) = $event->getController();

        if($controller instanceof UrlGeneratorInjectable) {
            $controller->setUrlGenerator(
                new UrlGenerator($this->routes, $this->context)
            );
        }
    }
}