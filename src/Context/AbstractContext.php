<?php

declare(strict_types=1);

namespace App\Context;

use App\Context\Exception\Config\NotCallableRoutesConfigException;
use App\Context\Exception\Config\NotCallableServicesConfigException;
use App\Context\Exception\Config\RoutesConfigNotFoundException;
use App\Context\Exception\Config\ServicesConfigNotFoundException;
use App\Context\Interface\ContextInterface;
use App\Kernel;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

abstract class AbstractContext implements ContextInterface
{

    public const string CONFIG_DIR = 'config';

    public const string ROUTES_CONFIG_FILE = 'routes.php';
    public const string SERVICES_CONFIG_FILE = 'services.php';

    /**
     * @throws NotCallableRoutesConfigException
     * @throws RoutesConfigNotFoundException
     */
    public function initRoutes(RoutingConfigurator $configurator): void
    {
        $routesConfigFile = $this->getContextDir() . '/' . self::CONFIG_DIR . '/' . self::ROUTES_CONFIG_FILE;

        if (file_exists($routesConfigFile) === false) {
            throw new RoutesConfigNotFoundException(self::class);
        }

        $routesConfig = require $routesConfigFile;

        if (is_callable($routesConfig) === false) {
            throw new NotCallableRoutesConfigException(self::class, $routesConfigFile);
        }

        /** @var callable(RoutingConfigurator $routes): void $routesConfig */
        $routesConfig($configurator);
    }

    /**
     * @throws NotCallableServicesConfigException
     * @throws ServicesConfigNotFoundException
     */
    public function initServices(ContainerConfigurator $configurator): void
    {
        $servicesConfigFile = $this->getContextDir() . '/' . self::CONFIG_DIR . '/' . self::SERVICES_CONFIG_FILE;

        if (file_exists($servicesConfigFile) === false) {
            throw new ServicesConfigNotFoundException(self::class);
        }

        $servicesConfig = require $servicesConfigFile;

        if (is_callable($servicesConfig) === false) {
            throw new NotCallableServicesConfigException(self::class, $servicesConfigFile);
        }

        /** @var callable(ContainerConfigurator $configurator): void $servicesConfig */
        $servicesConfig($configurator);
    }

    public function getRegistryMigrationParams(): ?array
    {
        $migrationsPath = $this->getMigrationsPathAsComposer();

        if ($migrationsPath === null) {
            return null;
        }

        return [Kernel::getNamespaceByPath($migrationsPath), $migrationsPath];
    }

    protected function getMigrationsPathAsComposer(): ?string
    {
        return null;
    }

    abstract protected function getContextDir(): string;
}
