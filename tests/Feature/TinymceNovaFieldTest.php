<?php

declare(strict_types=1);

namespace Greg\TinymceNova\Tests\Feature;

use Greg\TinymceNova\Tests\TestCase;
use Greg\TinymceNova\Tinymce;
use Laravel\Nova\Fields\Field;

final class TinymceNovaFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! class_exists(Field::class)) {
            $this->markTestSkipped('Requires Nova; run these integration tests against the host application.');
        }
    }

    public function test_default_source_and_content_defaults_are_exposed(): void
    {
        config()->set('tinymce-nova.source', 'cdn');
        config()->set('tinymce-nova.defaults.content_css', '/editor.css');
        config()->set('tinymce-nova.defaults.body_class', 'recipe-editor');
        $field = Tinymce::make('Content');

        $this->assertSame('cdn', $field->meta['source']);
        $this->assertSame('/editor.css', $field->meta['content_css']);
        $this->assertSame('recipe-editor', $field->meta['body_class']);
    }

    public function test_switching_sources_uses_the_matching_configuration(): void
    {
        config()->set('tinymce-nova.local.base_url', '/local-editor');
        config()->set('tinymce-nova.cdn.base_url', 'https://cdn.example/1');
        config()->set('tinymce-nova.cdn.api_key', 'test-key');
        config()->set('tinymce-nova.cdn.version', '8');
        $field = Tinymce::make('Content')->height(500)->useCdn();

        $this->assertSame('https://cdn.example/1', $field->meta['base_url']);
        $this->assertSame('test-key', $field->meta['api_key']);
        $this->assertSame('8', $field->meta['version']);
        $this->assertSame(500, $field->meta['height']);
        $field->useLocal();
        $this->assertSame('/local-editor', $field->meta['base_url']);
    }

    public function test_display_remains_plain_text_and_accepts_null(): void
    {
        $field = Tinymce::make('Content', 'content');
        $field->resolveForDisplay((object) ['content' => '<p><strong>Recipe</strong></p><img src=x onerror=alert(1)>']);
        $this->assertSame('Recipe', $field->value);
        $field->resolveForDisplay((object) ['content' => null]);
        $this->assertSame('', $field->value);
    }

    public function test_display_preserves_instruction_boundaries_and_decodes_entities(): void
    {
        $field = Tinymce::make('Instructions', 'instructions');
        $resource = (object) ['instructions' => '<p>1. Cream&nbsp;the yolks &amp; sugar.</p><p>2. Fold in the whites.<br>Chill.</p>'];
        $field->resolveForDisplay($resource);
        $this->assertSame("1. Cream the yolks & sugar.\n2. Fold in the whites.\nChill.", $field->value);
        $this->assertSame('<p>1. Cream&nbsp;the yolks &amp; sugar.</p><p>2. Fold in the whites.<br>Chill.</p>', $resource->instructions);
    }

    public function test_display_preserves_list_boundaries_without_returning_raw_html(): void
    {
        $field = Tinymce::make('Instructions', 'instructions');
        $field->resolveForDisplay((object) ['instructions' => '<ol><li>Mix <strong>well</strong>.</li><li>Chill &lt;overnight&gt;.</li></ol>']);
        $this->assertSame("Mix well.\nChill <overnight>.", $field->value);
    }
}
