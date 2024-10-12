<?php

declare(strict_types=1);

namespace App\Context\Interface;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

interface ContextInterface
{
    public function initRoutes(RoutingConfigurator $configurator): void;

    public function initServices(ContainerConfigurator $configurator): void;

    public function getRegistryMigrationParams(): ?array;
}
