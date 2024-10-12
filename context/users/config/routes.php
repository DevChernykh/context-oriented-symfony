<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('users_hello', '/users')
        ->controller(\Context\Users\Controller\UsersController::class)
        ->methods(['GET']);
};
