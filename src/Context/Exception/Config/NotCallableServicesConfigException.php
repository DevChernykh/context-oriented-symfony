<?php

declare(strict_types=1);

namespace App\Context\Exception\Config;

use App\Context\Exception\AbstractContextException;

final class NotCallableServicesConfigException extends AbstractContextException
{
    public function __construct(string $contextClass, string $configPath)
    {
        $this->message = <<<MSG_ERROR
            The service configuration file should return a "callable" object.
            
            Context: "{$contextClass}"
            Config file: "{$configPath}"
            
            For example:

            use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
            
            return function (ContainerConfigurator \$container): void { /** your code */ };
        MSG_ERROR;

        parent::__construct($contextClass);
    }
}
