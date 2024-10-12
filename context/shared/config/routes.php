<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('shared_hello', '/shared')
        ->controller(\Context\Shared\Controller\SharedController::class)
        ->methods(['GET']);
};
