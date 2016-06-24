<?php

namespace FDevs\Bridge\Sitemap\EventListener;

use FDevs\Bridge\Sitemap\Command\GenerateCommand;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SetAnonymousTokenSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * SetAnonymousTokenSubscriber constructor.
     *
     * @param TokenStorageInterface|null $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage = null)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [ConsoleEvents::COMMAND => ['setToken', 10]];
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function setToken(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        if ($command instanceof GenerateCommand && $this->tokenStorage) {
            $this->tokenStorage->setToken(new AnonymousToken('command', 'command'));
        }
    }
}
