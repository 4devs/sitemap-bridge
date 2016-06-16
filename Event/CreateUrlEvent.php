<?php

namespace FDevs\Bridge\Sitemap\Event;

use FDevs\Sitemap\Model\Url;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Routing\Route;

class CreateUrlEvent extends Event
{
    /**
     * @var Url
     */
    protected $url;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $name;

    /**
     * CreateUrlEvent constructor.
     *
     * @param Url    $url
     * @param Route  $route
     * @param array  $params
     * @param string $name
     */
    public function __construct(Url $url, Route $route, array $params, $name)
    {
        $this->url = $url;
        $this->route = $route;
        $this->params = $params;
        $this->name = $name;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
