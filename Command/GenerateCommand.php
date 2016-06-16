<?php

namespace FDevs\Bridge\Sitemap\Command;

use FDevs\Sitemap\Factory\UrlSet;
use FDevs\Sitemap\SitemapManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RequestContextAwareInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GenerateCommand extends Command
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RequestContextAwareInterface
     */
    private $requestContextAware;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var UrlSet
     */
    private $manager;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $webDir;

    /**
     * @var array
     */
    private $params;

    /**
     * {@inheritdoc}
     */
    public function __construct(SitemapManager $manager, $fileName, $webDir, array $params = [], $name = 'fdevs:sitemap:generate', TokenStorageInterface $tokenStorage = null, RequestContextAwareInterface $requestContextAware = null, $domain = '')
    {
        $this->tokenStorage = $tokenStorage;
        $this->requestContextAware = $requestContextAware;
        $this->domain = $domain;
        $this->webDir = $webDir;
        $this->manager = $manager;
        $this->fileName = $fileName;
        $this->params = $params;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fdevs:sitemap:generate')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'set allowed type: '.implode(',', $this->manager->getAllowed()), 'sitemap')
            ->setDescription('Generate Sitemap')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setToken();
        $this->manager->get($input->getOption('type'))->saveFile(rtrim($this->webDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->fileName, $this->params);
        $output->writeln('<info>sitemap created</info>');
    }

    /**
     * set Anonymous Token.
     */
    private function setToken()
    {
        if ($this->tokenStorage && $this->requestContextAware && $this->domain) {
            $parse = parse_url($this->domain);
            $this->tokenStorage->setToken(new AnonymousToken('command', 'command'));
            $this->requestContextAware->getContext()->setHost($parse['host'])->setScheme($parse['scheme']);
        }
    }
}
