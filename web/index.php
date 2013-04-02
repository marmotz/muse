<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;


// Routes
$routes = new Routing\RouteCollection();
$routes->add(
    'Home',
    new Routing\Route(
        '/',
        array(
            '_controller' => 'Muse\\Controller\\Album::displayAction',
            'album'       => '',
            'page'        => 1,
            'nbPerPage'   => 50,
        )
    )
);

$routes->add(
    'AlbumDisplay',
    new Routing\Route(
        '/album/{album}/{page}-{nbPerPage}',
        array(
            '_controller' => 'Muse\\Controller\\Album::displayAction',
            'page'        => 1,
            'nbPerPage'   => 50,
        ),
        array(
            'album'     => '.*',
            'page'      => '[0-9]+',
            'nbPerPage' => '[0-9]+',
        )
    )
);

$routes->add(
    'PhotoThumb',
    new Routing\Route(
        '/photo/{width}x{height}/{photo}',
        array(
            '_controller' => 'Muse\\Controller\\Photo::thumbAction'
        ),
        array(
            'photo'  => '.*',
            'width'  => '[0-9]+',
            'height' => '[0-9]+',
        )
    )
);

$routes->add(
    'PhotoDisplay',
    new Routing\Route(
        '/photo/{photo}',
        array(
            '_controller' => 'Muse\\Controller\\Photo::displayAction'
        ),
        array(
            'photo' => '.*'
        )
    )
);

// RUN
$request   = Request::createFromGlobals();
$framework = new Simplex\Framework($routes);

// $framework->addEventDispatcherSubscriber(new Simplex\Listener\GoogleAnalytics());
$framework->addEventDispatcherSubscriber(new HttpKernel\EventListener\ExceptionListener('Muse\\Controller\\Error::exceptionAction'));

$framework->handle($request)->send();