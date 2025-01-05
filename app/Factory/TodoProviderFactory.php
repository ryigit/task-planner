<?php

namespace App\Factory;

use App\Models\Provider;
use App\Services\Interface\ToDoProviderInterface;
use App\Services\Provider\DefaultProvider;
use InvalidArgumentException;

class TodoProviderFactory
{
    public function create(string $providerType, Provider $config = null): ToDoProviderInterface
    {
        return match ($providerType) {
            'default' => new DefaultProvider($config),
            default => throw new InvalidArgumentException('Invalid Provider'),
        };
    }
}
