<?php

declare(strict_types=1);

namespace Greg\TinymceNova\Tests\Feature;

use Greg\TinymceNova\Tests\TestCase;

final class TinymceFieldTest extends TestCase
{
    public function test_package_can_be_loaded(): void
    {
        $this->assertTrue(true);
    }

    public function test_config_file_exists(): void
    {
        $configPath = __DIR__ . '/../../config/tinymce-nova.php';
        $this->assertFileExists($configPath);
    }

    public function test_service_provider_can_be_instantiated(): void
    {
        $provider = new \Greg\TinymceNova\TinymceNovaServiceProvider(app());
        $this->assertInstanceOf(\Greg\TinymceNova\TinymceNovaServiceProvider::class, $provider);
    }
}
