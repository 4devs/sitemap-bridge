<?php

namespace FDevs\Bridge\Sitemap\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FactoryPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('f_devs_sitemap.sitemap_manager')) {
            $definition = $container->getDefinition('f_devs_sitemap.sitemap_manager');
            $taggedServices = $container->findTaggedServiceIds('f_devs_sitemap.factory');
            foreach ($taggedServices as $id => $attributes) {
                $definition->addMethodCall('add', [new Reference($id)]);
            }
        }
    }
}
