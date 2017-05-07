<?php
/**
 * container-interop-doctrine
 *
 * @link      http://github.com/DASPRiD/container-interop-doctrine For the canonical source repository
 * @copyright 2016 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace ContainerInteropDoctrine;

use Doctrine\DBAL\Migrations\Configuration\Configuration as MigrationsConfiguration;
use Interop\Container\ContainerInterface;

/**
 * @method MigrationsConfiguration __invoke(ContainerInterface $container)
 */
class MigrationsConfigurationFactory extends AbstractFactory
{
    /**
     * @inheritDoc
     */
    protected function createWithConfig(ContainerInterface $container, $configKey)
    {
        $config = $this->retrieveConfig($container, $configKey, 'migrations_configuration');
        $configuration = new MigrationsConfiguration(
            $this->retrieveDependency(
                $container,
                $config['connection'],
                'connection',
                ConnectionFactory::class
            )
        );
        $configuration->setName($config['name']);
        $configuration->setMigrationsDirectory($config['directory']);
        $configuration->setMigrationsNamespace($config['namespace']);
        $configuration->setMigrationsTableName($config['table']);
        $configuration->registerMigrationsFromDirectory($config['directory']);

        if (method_exists($configuration, 'setMigrationsColumnName')) {
            $configuration->setMigrationsColumnName($config['column']);
        }

        return $configuration;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultConfig($configKey)
    {
        return [
            'directory' => 'data/DoctrineORMModule/Migrations',
            'name'      => 'Doctrine Database Migrations',
            'namespace' => 'DoctrineORMModule\Migrations',
            'table'     => 'migrations',
            'column'    => 'version',
        ];
    }
}
