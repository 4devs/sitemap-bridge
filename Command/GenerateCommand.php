<?php

namespace FDevs\Bridge\Sitemap\Command;

use FDevs\Sitemap\Factory\UrlSet;
use FDevs\Sitemap\SitemapManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
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
    public function __construct(SitemapManager $manager, $fileName, $webDir, array $params = [], $name = 'fdevs:sitemap:generate')
    {
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
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'set allowed type: '.implode(',', $this->manager->getAllowed()), 'sitemap')
            ->setDescription('Generate Sitemap')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager->get($input->getOption('type'))->saveFile(rtrim($this->webDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->fileName, $this->params);
        $output->writeln('<info>sitemap created</info>');
    }
}
