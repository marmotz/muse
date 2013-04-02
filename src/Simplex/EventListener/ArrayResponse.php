<?php

namespace Simplex\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\Helper\AssetsHelper;
use Symfony\Component\Templating\Asset\PathPackage;

class ArrayResponse implements EventSubscriberInterface
{
    protected $routes;
    protected $context;


    public function __construct(RouteCollection $routes, RequestContext $context) {
        $this->routes  = $routes;
        $this->context = $context;
    }

    public function onView(GetResponseForControllerResultEvent $event)
    {
        $response = $event->getControllerResult();

        if (is_array($response)) {
            list($controller, $action) = explode('::', $event->getRequest()->attributes->get('_controller'));

            $class = new \ReflectionClass($controller);
            $controllerFilename = $class->getFileName();
            $viewsPath = dirname(dirname($controllerFilename)) . '/Views/%name%';

            $view = new PhpEngine(
                new TemplateNameParser(),
                new FilesystemLoader($viewsPath)
            );

            $view->set(new SlotsHelper());
            $view->set(
                new AssetsHelper(
                    sprintf(
                        '%s/assets/%s',
                        $this->context->getBaseurl(),
                        strtolower(
                            substr(
                                $controller,
                                0,
                                strpos($controller, '\\')
                            )
                        )
                    )
                )
            );

            $templateFilename = sprintf(
                '%s/%s.php',
                substr(basename($controllerFilename), 0, -4),
                substr($action, 0, -6)
            );

            $event->setResponse(
                new Response(
                    $view->render(
                        $templateFilename,
                        array_merge(
                            $response,
                            array(
                                'u' => new UrlGenerator($this->routes, $this->context)
                            )
                        )
                    )
                )
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return array('kernel.view' => 'onView');
    }
}