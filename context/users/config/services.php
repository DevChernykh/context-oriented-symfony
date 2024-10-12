<?php

declare(strict_types=1);

use Context\Users\Controller;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    /** Controllers */
    $services->set(Controller\UsersController::class)->public();
};
