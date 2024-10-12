<?php

declare(strict_types=1);

namespace App\Context\Exception\Config;

use App\Context\Exception\AbstractContextException;

final class ServicesConfigNotFoundException extends AbstractContextException
{
    public function __construct(string $contextClass)
    {
        $this->message = 'No services are defined for the "' . $contextClass . '" context.';

        parent::__construct($contextClass);
    }
}
