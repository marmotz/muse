<?php

namespace Simplex\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EntityManagerInjector implements EventSubscriberInterface {
    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::CONTROLLER => 'onController'
        );
    }

    public function onController(FilterControllerEvent $event) {
        $controller = $event->getController();

        if($controller instanceof EntityManagerInjectable) {
            $controller->setEntityManager($this->em);
        }
    }
}