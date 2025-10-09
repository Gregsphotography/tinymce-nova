<?php

declare(strict_types=1);

namespace Greg\TinymceNova\Tests\Feature;

use Greg\TinymceNova\Tests\TestCase;
use Greg\TinymceNova\Tinymce;

final class TinymceFieldTest extends TestCase
{
    public function test_can_create_tinymce_field(): void
    {
        $field = Tinymce::make('Content');

        $this->assertEquals('Content', $field->name);
        $this->assertEquals('tinymce', $field->component);
    }

    public function test_can_set_height(): void
    {
        $field = Tinymce::make('Content')->height(500);

        $this->assertEquals(500, $field->meta['height']);
    }

    public function test_can_set_toolbar(): void
    {
        $toolbar = 'undo redo | bold italic';
        $field = Tinymce::make('Content')->toolbar($toolbar);

        $this->assertEquals($toolbar, $field->meta['toolbar']);
    }

    public function test_can_set_plugins_as_array(): void
    {
        $plugins = ['lists', 'link', 'image'];
        $field = Tinymce::make('Content')->plugins($plugins);

        $this->assertEquals('lists link image', $field->meta['plugins']);
    }

    public function test_can_set_plugins_as_string(): void
    {
        $plugins = 'lists link image';
        $field = Tinymce::make('Content')->plugins($plugins);

        $this->assertEquals($plugins, $field->meta['plugins']);
    }

    public function test_can_set_menubar(): void
    {
        $field = Tinymce::make('Content')->menubar(false);

        $this->assertFalse($field->meta['menubar']);
    }

    public function test_can_set_statusbar(): void
    {
        $field = Tinymce::make('Content')->statusbar(false);

        $this->assertFalse($field->meta['statusbar']);
    }

    public function test_can_set_branding(): void
    {
        $field = Tinymce::make('Content')->branding(true);

        $this->assertTrue($field->meta['branding']);
    }

    public function test_can_set_resize(): void
    {
        $field = Tinymce::make('Content')->resize(false);

        $this->assertFalse($field->meta['resize']);
    }

    public function test_can_set_custom_options(): void
    {
        $options = [
            'content_css' => '/css/custom-editor.css',
            'body_class' => 'my-editor-class'
        ];
        $field = Tinymce::make('Content')->options($options);

        $this->assertEquals($options['content_css'], $field->meta['content_css']);
        $this->assertEquals($options['body_class'], $field->meta['body_class']);
    }

    public function test_can_use_local_source(): void
    {
        $field = Tinymce::make('Content')->useLocal();

        $this->assertEquals('local', $field->meta['source']);
    }

    public function test_can_use_cdn_source(): void
    {
        $field = Tinymce::make('Content')->useCdn();

        $this->assertEquals('cdn', $field->meta['source']);
    }

    public function test_can_set_license_key(): void
    {
        $field = Tinymce::make('Content')->licenseKey('commercial-license');

        $this->assertEquals('commercial-license', $field->meta['license_key']);
    }

    public function test_can_set_api_key(): void
    {
        $field = Tinymce::make('Content')->apiKey('api-key-123');

        $this->assertEquals('api-key-123', $field->meta['api_key']);
    }

    public function test_can_set_base_url(): void
    {
        $field = Tinymce::make('Content')->baseUrl('/custom-tinymce');

        $this->assertEquals('/custom-tinymce', $field->meta['base_url']);
    }

    public function test_can_set_script_path(): void
    {
        $field = Tinymce::make('Content')->scriptPath('/js/custom/tinymce.min.js');

        $this->assertEquals('/js/custom/tinymce.min.js', $field->meta['script_path']);
    }

    public function test_can_set_version(): void
    {
        $field = Tinymce::make('Content')->version('6');

        $this->assertEquals('6', $field->meta['version']);
    }
}
