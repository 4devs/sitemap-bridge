<?php

namespace FDevs\Bridge\Sitemap\EventListener;

use FDevs\Bridge\Sitemap\Event\CreateUrlEvent;
use FDevs\Bridge\Sitemap\SitemapEvents;
use FDevs\Sitemap\Model\HrefLang;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Exception\ExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddHrefLangSubscriber implements EventSubscriberInterface
{
    const PARAM_KEY = '_locale';
    /**
     * @var array
     */
    private $params = [];

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * AddHrefLangSubscriber constructor.
     *
     * @param array                 $params
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(array $params, UrlGeneratorInterface $urlGenerator)
    {
        $this->params = $params;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapEvents::CREATE_URL => [
                ['addHrefLang', 10],
            ],
        ];
    }

    /**
     * @param CreateUrlEvent $event
     */
    public function addHrefLang(CreateUrlEvent $event)
    {
        $params = $event->getParams();
        if (isset($params[self::PARAM_KEY])) {
            $url = $event->getUrl();
            foreach ($this->params as $param) {
                if (isset($param[self::PARAM_KEY]) && $param[self::PARAM_KEY] !== $params[self::PARAM_KEY]) {
                    try {
                        $path = $this->urlGenerator->generate($event->getName(), array_intersect_key($param, array_flip($event->getRoute()->compile()->getVariables())), UrlGeneratorInterface::ABSOLUTE_URL);
                        $url->addElement(
                            new HrefLang($param[self::PARAM_KEY], $path)
                        );
                    } catch (ExceptionInterface $e) {
                    }
                }
            }
        }
    }
}
