<?php

namespace FDevs\Bridge\Sitemap\EventListener;

use FDevs\Bridge\Sitemap\Command\GenerateCommand;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RequestContextAwareInterface;

class SetRequestContextAwareSubscriber implements EventSubscriberInterface
{
    /**
     * @var RequestContextAwareInterface
     */
    private $requestContextAware;

    /**
     * @var string
     */
    private $domain;

    /**
     * SetRequestContextAwareSubscriber constructor.
     *
     * @param RequestContextAwareInterface|null $requestContextAware
     * @param string                            $domain
     */
    public function __construct(RequestContextAwareInterface $requestContextAware = null, $domain = '')
    {
        $this->requestContextAware = $requestContextAware;
        $this->domain = $domain;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [ConsoleEvents::COMMAND => ['setRequestContext', 10]];
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function setRequestContext(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        if ($command instanceof GenerateCommand && $this->requestContextAware && $this->domain) {
            $parse = parse_url($this->domain);
            $this->requestContextAware->getContext()->setHost($parse['host'])->setScheme($parse['scheme']);
        }
    }
}
