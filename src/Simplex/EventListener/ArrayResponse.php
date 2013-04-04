<?php

namespace Simplex\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\Helper\AssetsHelper;
use Symfony\Component\Templating\Asset\PathPackage;

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

            $view = new PhpEngine(
                new TemplateNameParser(),
                new FilesystemLoader(
                    $resourcePath . '/views/%name%'
                )
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

            // i18n
            $translator = new Translator($request->getSession()->get('_locale'));
            $translator->setFallbackLocale('en');
            $translator->addLoader('yml', new YamlFileLoader());
            $translator->addResource('yml', $resourcePath . '/i18n/fr.yml', 'fr');

            // Routes
            $urlGenerator = new UrlGenerator($this->routes, $this->context);

            $event->setResponse(
                new Response(
                    $view->render(
                        sprintf(
                            '%s/%s.php',
                            substr(basename($controllerFilename), 0, -4),
                            substr($action, 0, -6)
                        ),
                        array_merge(
                            $response,
                            array(
                                '_controller' => sprintf(
                                    '%s%sController',
                                    substr($controller, 0, strpos($controller, '\\')),
                                    substr($controller, strrpos($controller, '\\') + 1)
                                ),
                                '_action' => ucfirst($action),
                                '_request' => $request,
                                '_session' => $request->getSession(),

                                'url' => function($name, $parameters = array(), $referenceType = UrlGenerator::ABSOLUTE_PATH) use($urlGenerator) {
                                    return $urlGenerator->generate($name, $parameters, $referenceType);
                                },
                                '_' => function($id, array $parameters = array(), $domain = 'messages', $locale = null) use($translator) {
                                    return $translator->trans($id, $parameters, $domain, $locale);
                                },
                            )
                        )
                    )
                )
            );
        }
    }
}