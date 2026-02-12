<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Tests;

use Illuminate\Support\Facades\Http;
use Misaf\VendraCustomPage\CustomPageServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Override;

abstract class TestCase extends TestbenchTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    protected function getPackageProviders($app): array
    {
        return [
            CustomPageServiceProvider::class,
        ];
    }
}
