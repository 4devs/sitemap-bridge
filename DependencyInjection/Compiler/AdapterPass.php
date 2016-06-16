<?php

namespace FDevs\Bridge\Sitemap\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AdapterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('f_devs_sitemap.factory.urlset')) {
            $definition = $container->getDefinition('f_devs_sitemap.factory.urlset');

            $taggedServices = $container->findTaggedServiceIds(
                'f_devs_sitemap.adapter'
            );
            foreach ($taggedServices as $id => $attributes) {
                $definition->addMethodCall('addAdapter', [new Reference($id)]);
            }
        }
    }
}
