<?php

namespace FDevs\Bridge\Sitemap\DependencyInjection;

use FDevs\Sitemap\Util\Params;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FDevsSitemapExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter($this->getAlias().'.dir', $config['web_dir'] ?: $container->getParameter('kernel.root_dir').'/../web');

        $container->setParameter($this->getAlias().'.uri', $config['uri']);
        $container->setParameter($this->getAlias().'.filename', $config['filename']);
        $container->setParameter($this->getAlias().'.params', Params::prepare($config['parameters']));

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');
        $loader->load('console.xml');
        foreach ($config['adapter'] as $item) {
            $container->setParameter($this->getAlias().'.adapter.'.$item['type'].'.class', $item['class']);
            $container->setParameter($this->getAlias().'.adapter.'.$item['type'].'.manager_name', $item['manager']);
            $loader->load(sprintf('adapter/%s.xml', $item['type']));
        }
    }
}
