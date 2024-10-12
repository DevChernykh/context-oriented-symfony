<?php

declare(strict_types=1);

namespace App\Context\Exception;

use ErrorException;

abstract class AbstractContextException extends ErrorException
{

    public function __construct(
        protected readonly string $contextClass,
    ) {
        parent::__construct();
    }
}
