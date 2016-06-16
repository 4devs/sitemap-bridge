<?php

namespace FDevs\Bridge\Sitemap\Adapter;

use FDevs\Bridge\Sitemap\Util\Routing;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use ArrayIterator;

class StaticRouting extends AbstractRouting
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * {@inheritdoc}
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, $eventDispatcher, RouterInterface $router)
    {
        parent::__construct($urlGenerator, $eventDispatcher);
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemList(array $params = [])
    {
        $iterator = new ArrayIterator();
        $routes = array_filter(
            $this->router->getRouteCollection()->all(),
            function (Route $route) use ($params) {
                return $route->hasDefault('sitemap') && $route->getDefault('sitemap') && Routing::isRouteVariablesComplete($route, $params);
            }
        );
        /**
         * @var string
         * @var \Symfony\Component\Routing\Route $route
         */
        foreach ($routes as $name => $route) {
            $name = method_exists($route, 'getName') ? $route->getName() : $name;
            if (is_string($name)) {
                $iterator[$name] = $route;
            }
        }

        return $iterator;
    }
}
