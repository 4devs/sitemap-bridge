<?php

namespace FDevs\Bridge\Sitemap\Adapter;

use FDevs\Bridge\Sitemap\Event\CreateUrlEvent;
use FDevs\Bridge\Sitemap\SitemapEvents;
use FDevs\Bridge\Sitemap\Util\Routing;
use FDevs\Sitemap\Adapter\AbstractAdapter;
use FDevs\Sitemap\Model\LastModification;
use FDevs\Sitemap\Model\Url;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Route;

abstract class AbstractRouting extends AbstractAdapter
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * AbstractRouting constructor.
     *
     * @param UrlGeneratorInterface         $urlGenerator
     * @param EventDispatcherInterface|null $eventDispatcher
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, EventDispatcherInterface $eventDispatcher = null)
    {
        $this->urlGenerator = $urlGenerator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $name
     * @param array  $params
     * @param mixed  $item
     *
     * @return Url|null
     */
    public function createUrl($name, array $params = [], $item)
    {
        $url = new Url(
            $this->urlGenerator->generate(
                $name,
                array_intersect_key($params, array_flip($item->compile()->getVariables())),
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
        if ($item->hasDefault('priority')) {
            $url->setPriority(floatval($item->getDefault('priority')));
        }
        if ($item->hasDefault('lastmod')) {
            $lastmod = $item->getDefault('lastmod');
            if (is_string($lastmod)) {
                $lastmod = new \DateTime($lastmod);
            }
            if ($lastmod instanceof \DateTime || $lastmod instanceof LastModification) {
                $url->setLastMod($lastmod);
            }
        }
        if ($item->hasDefault('changefreq')) {
            $url->setChangeFreq($item->getDefault('changefreq'));
        }
        $this->dispatch(SitemapEvents::CREATE_URL, new CreateUrlEvent($url, $item, $params, $name));

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted($item, array $params)
    {
        return $item instanceof Route && Routing::isRouteVariablesComplete($item, $params);
    }

    /**
     * @param string $name
     * @param Event  $event
     */
    private function dispatch($name, Event $event)
    {
        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch($name, $event);
        }
    }
}
