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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

use Atlas\Cli\Config;
use Atlas\Cli\Fsio;
use Atlas\Cli\Logger;
use Atlas\Cli\Skeleton;
use Atlas\Orm\Atlas;
use Atlas\Symfony\Command\SkeletonCommand;
use Atlas\Symfony\DataCollector\AtlasCollector;

/**
 * Atlas extension for Symfony.
 */
class AtlasExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $containerBuilder)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $containerBuilder->register(Atlas::class)
            ->setFactory([Factory::class, 'newAtlas'])
            ->addArgument($config)
            ->setAutowired(true);

        $containerBuilder->register(Config::class)
            ->setFactory([Factory::class, 'newConfig'])
            ->addArgument($config);

        $containerBuilder->register(Skeleton::class)
            ->setAutowired(true);

        $containerBuilder->register(Fsio::class);

        $containerBuilder->register(Logger::class);

        $containerBuilder->register(SkeletonCommand::class)
            ->addTag('console.command')
            ->setAutowired(true);

        $containerBuilder->register(AtlasCollector::class)
            ->addTag('data_collector', [
                'template' => '@Atlas/Collector/atlas.html.twig',
                'id' => 'atlas.collector'
            ])
            ->setAutowired(true);
    }
}
