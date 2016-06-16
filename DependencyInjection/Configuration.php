<?php

namespace FDevs\Bridge\Sitemap\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('f_devs_sitemap');
        $db = ['mongodb'];
        $rootNode
            ->children()
                ->scalarNode('web_dir')->defaultNull()->end()
                ->arrayNode('adapter')
                    ->defaultValue([])
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')
                                ->isRequired()
                                ->validate()
                                ->ifNotInArray($db)
                                    ->thenInvalid('Invalid database adapter "%s"')
                                ->end()
                            ->end()
                            ->scalarNode('class')->isRequired()->end()
                            ->scalarNode('manager')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('filename')->defaultValue('sitemap.xml')->end()
                ->scalarNode('uri')->isRequired()->end()
                ->arrayNode('parameters')
                    ->useAttributeAsKey('name')
                    ->defaultValue([])
                    ->prototype('array')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
