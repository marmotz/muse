<?php

namespace Simplex;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

class Framework extends HttpKernel\HttpKernel {
    public function __construct($projectsRootPath) {
        $projectsRootPath = (array) $projectsRootPath;

        $routes = new Routing\RouteCollection;

        foreach($projectsRootPath as $projectRootPath) {
            $loader = new Routing\Loader\YamlFileLoader(
                new FileLocator($projectRootPath . '/resources')
            );

            $routes->addCollection(
                $loader->load('routes.yml')
            );
        }

        $context  = new Routing\RequestContext();
        $matcher  = new Routing\Matcher\UrlMatcher($routes, $context);
        $resolver = new HttpKernel\Controller\ControllerResolver();

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new EventListener\SessionStarter());
        $dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher));
        $dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
        $dispatcher->addSubscriber(new EventListener\ArrayResponse($routes, $context));
        $dispatcher->addSubscriber(new EventListener\UrlGeneratorInjector($routes, $context));
        $dispatcher->addSubscriber(new EventListener\StringResponse());
        $dispatcher->addSubscriber(new EventListener\ContentLength());

        parent::__construct($dispatcher, $resolver);
    }

    public function addEventDispatcherSubscriber(EventSubscriberInterface $subscriber) {
        $this->dispatcher->addSubscriber($subscriber);

        return $this;
    }

    public function setErrorController($errorController) {
        return $this->addEventDispatcherSubscriber(
            new HttpKernel\EventListener\ExceptionListener(
                $errorController
            )
        );
    }

    public function setDefaultLocale($defaultLocale) {
        return $this->addEventDispatcherSubscriber(
            new EventListener\LocaleLoader(
                $defaultLocale
            )
        );
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager) {
        return $this->addEventDispatcherSubscriber(
            new EventListener\EntityManagerInjector(
                $entityManager
            )
        );
    }
}