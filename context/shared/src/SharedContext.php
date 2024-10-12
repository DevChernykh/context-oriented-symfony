<?php

declare(strict_types=1);

namespace Context\Shared;

use App\Context\AbstractContext;

final class SharedContext extends AbstractContext
{

    protected function getMigrationsPathAsComposer(): string
    {
        return 'context/shared/migrations';
    }

    protected function getContextDir(): string
    {
        return __DIR__ . '/..';
    }
}
