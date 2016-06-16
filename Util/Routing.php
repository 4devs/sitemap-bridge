<?php

namespace FDevs\Bridge\Sitemap\Util;

use Symfony\Component\Routing\Route;

class Routing
{
    /**
     * is Route Variables Complete.
     *
     * @param Route $route
     * @param array $variables
     *
     * @return bool
     */
    public static function isRouteVariablesComplete(Route $route, array $variables)
    {
        return !count(
            array_diff($route->compile()->getVariables(), array_keys(array_merge($variables, $route->getDefaults())))
        );
    }
}
