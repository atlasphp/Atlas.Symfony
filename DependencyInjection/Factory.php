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

use Atlas\Cli\Config;
use Atlas\Cli\Transform;
use Atlas\Orm\Atlas;
use Atlas\Orm\AtlasBuilder;
use Atlas\Orm\Transaction\AutoCommit;
use Atlas\Pdo\Connection;
use Atlas\Pdo\ConnectionLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Factory for Atlas service objects.
 */
class Factory
{
    /**
     * Returns a new Atlas ORM object.
     *
     * @param array $config The 'atlas' config array.
     * @param ContainerInterface $container The Symfony container.
     * @return Atlas
     */
    public static function newAtlas(array $config, ContainerInterface $container) : Atlas
    {
        $spec = $config['pdo']['connection_locator']['default'] ?? [];
        $args = self::getPdoArgs($spec);
        $builder = new AtlasBuilder(...$args);

        $connectionLocator = $builder->getConnectionLocator();
        self::addConnections($connectionLocator, 'read', $config);
        self::addConnections($connectionLocator, 'write', $config);

        $transactionClass = $config['orm']['atlas']['transaction_class']
            ?? AutoCommit::CLASS;
        if ($transactionClass == 'Atlas\\Orm\\AutoCommit') {
            // make allowance for a buggy config from earlier releases
            $transactionClass = AutoCommit::CLASS;
        }
        $builder->setTransactionClass($transactionClass);

        $factory = function ($class) use ($container) {
            if ($container->has($class)) {
                return $container->get($class);
            }

            return new $class();
        };
        $builder->setFactory($factory);

        $atlas = $builder->newAtlas();

        if ($config['orm']['atlas']['log_queries']) {
            $atlas->logQueries();
        }

        return $atlas;
    }

    /**
     * Returns a new Atlas CLI config object.
     *
     * @param array $config The 'atlas' config array.
     * @return Config
     */
    public static function newConfig(array $config) : Config
    {
        $input = $config['cli']['config']['input'] ?? [];
        $input['pdo'] = self::getPdoArgs($input['pdo'] ?? []);
        $types = $config['cli']['transform']['types'] ?? [];
        $input['transform'] = new Transform($types);

        return new Config($input);
    }

    /**
     * Adds connection factories to the ConnectionLocator.
     *
     * @param ConnectionLocator $connectionLocator The ConnectionLocator.
     * @param string $type The type of connections ('read' or 'write').
     * @param array $config The 'atlas' config array.
     */
    protected static function addConnections(
        ConnectionLocator $connectionLocator,
        string $type,
        array $config
    ) : void
    {
        $specs = $config['pdo']['connection_locator'][$type] ?? [];
        $method = 'set' . ucfirst($type) . 'Factory';
        foreach ($specs as $name => $spec) {
            $args = self::getPdoArgs($spec);
            $connectionLocator->$method($name, Connection::factory(...$args));
        }
    }

    /**
     * Converts named PDO arguments to sequential ones.
     *
     * @param array $spec The argument specification.
     * @return array
     */
    protected static function getPdoArgs(array $spec) : array
    {
        return [
            $spec['dsn'] ?? null,
            $spec['username'] ?? null,
            $spec['password'] ?? null
        ];
    }
}
