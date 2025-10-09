<?php

declare(strict_types=1);

namespace Greg\TinymceNova\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            // No providers needed for basic tests
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Basic setup without Nova dependencies
        $app['config']->set('app.key', 'base64:UTyp33Kr3a91bV7hZfD2y2bGTxcMxgrTisrM1M6+c+o=');
    }
}
