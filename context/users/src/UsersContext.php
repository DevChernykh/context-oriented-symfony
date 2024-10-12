<?php

declare(strict_types=1);

namespace Context\Users;

use App\Context\AbstractContext;

final class UsersContext extends AbstractContext
{

    protected function getContextDir(): string
    {
        return __DIR__ . '/..';
    }
}
