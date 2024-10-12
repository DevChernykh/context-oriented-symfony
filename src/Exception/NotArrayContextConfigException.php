<?php

declare(strict_types=1);

namespace App\Exception;

use ErrorException;

final class NotArrayContextConfigException extends ErrorException
{
    public function __construct()
    {
        parent::__construct(
            message: 'The context configuration file should return an array.',
        );
    }
}
