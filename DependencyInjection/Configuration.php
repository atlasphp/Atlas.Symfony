<?php

/*
 * This file is part of the atlas/symfony package.
 *
 * (c) Atlas for PHP
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atlas\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration for Symfony.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('atlas');

        $rootNode->children()

            ->arrayNode('pdo')
                ->children()
                    ->arrayNode('connection_locator')
                        ->children()
                            ->arrayNode('default')
                                ->children()
                                    ->scalarNode('dsn')->isRequired()->end()
                                    ->scalarNode('username')->end()
                                    ->scalarNode('password')->end()
                                ->end()
                            ->end()
                            ->arrayNode('read')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('dsn')->isRequired()->end()
                                        ->scalarNode('username')->end()
                                        ->scalarNode('password')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('write')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('dsn')->isRequired()->end()
                                        ->scalarNode('username')->end()
                                        ->scalarNode('password')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->arrayNode('orm')
                ->children()
                    ->arrayNode('atlas')
                        ->children()
                            ->scalarNode('transaction_class')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->arrayNode('cli')
                ->children()
                    ->arrayNode('config')
                        ->children()
                            ->arrayNode('input')
                                ->children()
                                    ->arrayNode('pdo')
                                        ->isRequired()
                                        ->children()
                                            ->scalarNode('dsn')->isRequired()->end()
                                            ->scalarNode('username')->end()
                                            ->scalarNode('password')->end()
                                        ->end()
                                    ->end()
                                    ->scalarNode('directory')
                                    ->end()
                                    ->scalarNode('namespace')
                                    ->end()
                                    ->scalarNode('templates')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('transform')
                        ->children()
                            ->variableNode('types')
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('skeleton')
                        ->children()
                            ->scalarNode('config')
                            ->end()
                            ->scalarNode('fsio')
                            ->end()
                            ->scalarNode('logger')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
