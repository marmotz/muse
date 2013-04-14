<?php

namespace Simplex\EventListener;

use Simplex\Twig\Extension;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

use Symfony\Component\Finder\Finder;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;

use Symfony\Component\Templating\TemplateNameParser;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\YamlFileLoader;

class ArrayResponse implements EventSubscriberInterface {
    protected $routes;
    protected $context;


    public function __construct(RouteCollection $routes, RequestContext $context) {
        $this->routes  = $routes;
        $this->context = $context;
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::VIEW => 'onView'
        );
    }

    public function onView(GetResponseForControllerResultEvent $event) {
        $response = $event->getControllerResult();

        if (is_array($response)) {
            $request = $event->getRequest();

            list($controller, $action) = explode('::', $request->attributes->get('_controller'));

            $class = new \ReflectionClass($controller);
            $controllerFilename = $class->getFileName();
            $resourcePath = dirname(dirname($controllerFilename)) . '/resources';

            // twig
            $twig = new \Twig_Environment(
                new \Twig_Loader_Filesystem($resourcePath . '/views'),
                array(
                    'debug' => true,
                    'cache' => __DIR__ . '/../../../cache/tpl',
                )
            );

            $twig->addGlobal(
                '_controller',
                sprintf(
                    '%s%sController',
                    substr($controller, 0, strpos($controller, '\\')),
                    substr($controller, strrpos($controller, '\\') + 1)
                )
            );
            $twig->addGlobal('_action', ucfirst($action));
            $twig->addGlobal('_request', $request);
            $twig->addGlobal('_session', $request->getSession());

            // routing
            $twig->addExtension(
                new RoutingExtension(
                    new UrlGenerator($this->routes, $this->context)
                )
            );

            // assets
            $twig->addExtension(
                new Extension\Asset(
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

            // i18n
            $translator = new Translator($request->getSession()->get('_locale'));
            $translator->setFallbackLocale('en');
            $translator->addLoader('yml', new YamlFileLoader());

            $finder = new Finder();
            $finder
                ->files()
                ->in($resourcePath . '/i18n')
                ->depth(0)
                ->name('*.yml')
            ;

            foreach($finder as $resource) {
                $resource = $resource->getPathname();

                $translator->addResource('yml', $resource, basename(substr($resource, 0, strrpos($resource, '.'))));
            }

            $twig->addExtension(
                new TranslationExtension(
                    $translator
                )
            );


            // view
            $view = new TwigEngine(
                $twig,
                new TemplateNameParser()
            );

            $event->setResponse(
                new Response(
                    $view->render(
                        sprintf(
                            '%s/%s.twig',
                            substr(basename($controllerFilename), 0, -4),
                            substr($action, 0, -6)
                        ),
                        $response
                    )
                )
            );
        }
    }
}