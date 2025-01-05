<?php

namespace Tests\Unit\Factory;

use App\Factory\TodoProviderFactory;
use App\Models\Provider;
use App\Services\Provider\DefaultProvider;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TodoProviderFactoryTest extends TestCase
{
    #[Test]
    public function createsDefaultProvider()
    {
        $providerConfig = new Provider(
            [
                'type' => 'default',
                'config' => 'value'
            ]
        );
        $factory = new TodoProviderFactory();
        $provider = $factory->create('default', $providerConfig);
        $this->assertInstanceOf(DefaultProvider::class, $provider);
    }

    #[Test]
    public function throwsExceptionForInvalidProvider()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Provider');

        $factory = new TodoProviderFactory();
        $factory->create('invalid');
    }
}
