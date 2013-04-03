<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;

require __DIR__ . '/../etc/db.php';

// RUN
$framework = new Simplex\Framework('../src/Muse');

$framework
    ->addEventDispatcherSubscriber(
        new HttpKernel\EventListener\ExceptionListener(
            'Muse\\Controller\\Error::exceptionAction'
        )
    )
    ->addEventDispatcherSubscriber(
        new Simplex\EventListener\SessionStarter()
    )
    ->addEventDispatcherSubscriber(
        new Simplex\EventListener\LocaleLoader(
            'fr'
        )
    )
    ->addEventDispatcherSubscriber(
        new Simplex\EventListener\EntityManagerInjector(
            $entityManager
        )
    )
    // ->addEventDispatcherSubscriber(
    //     new Simplex\EventListener\GoogleAnalytics()
    // )
;

$framework->handle(
    Request::createFromGlobals()
)->send();