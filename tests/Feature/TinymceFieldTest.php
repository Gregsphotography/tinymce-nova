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
}
