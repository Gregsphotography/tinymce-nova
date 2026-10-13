<?php

declare(strict_types=1);

namespace Greg\TinymceNova;

use Illuminate\Support\Facades\Config;
use Laravel\Nova\Fields\Expandable;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\SupportsDependentFields;

final class Tinymce extends Field
{
    use Expandable;
    use SupportsDependentFields;

    public $component = 'tinymce';

    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta($this->getDefaultConfig());
    }

    protected function getDefaultConfig(?string $source = null): array
    {
        $defaults = Config::get('tinymce-nova.defaults', []);
        $source ??= Config::get('tinymce-nova.source', 'local');

        $config = [
            'source' => $source,
            'height' => $defaults['height'] ?? 400,
            'toolbar' => $defaults['toolbar'] ?? 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code',
            'plugins' => $defaults['plugins'] ?? 'lists link image code table',
            'menubar' => $defaults['menubar'] ?? true,
            'statusbar' => $defaults['statusbar'] ?? true,
            'branding' => $defaults['branding'] ?? false,
            'resize' => $defaults['resize'] ?? true,
            'content_css' => $defaults['content_css'] ?? null,
            'body_class' => $defaults['body_class'] ?? null,
        ];

        // Add source-specific configuration
        if ($source === 'local') {
            $localConfig = Config::get('tinymce-nova.local', []);
            $config['base_url'] = $localConfig['base_url'] ?? '/tinymce';
            $config['script_path'] = $localConfig['script_path'] ?? '/js/tinymce/tinymce.min.js';
            $config['license_key'] = $localConfig['license_key'] ?? 'gpl';
        } else {
            $cdnConfig = Config::get('tinymce-nova.cdn', []);
            $config['base_url'] = $cdnConfig['base_url'] ?? 'https://cdn.tiny.cloud/1';
            $config['api_key'] = $cdnConfig['api_key'] ?? 'no-api-key';
            $config['license_key'] = $cdnConfig['license_key'] ?? 'gpl';
            $config['version'] = $cdnConfig['version'] ?? '7';
        }

        // Add advanced configuration
        $advanced = Config::get('tinymce-nova.advanced', []);
        $config = array_merge($config, $advanced);

        // Add security configuration
        $security = Config::get('tinymce-nova.security', []);
        if (!empty($security)) {
            $config = array_merge($config, $security);
        }

        return $config;
    }

    public function height(int $height): self
    {
        return $this->withMeta(['height' => $height]);
    }

    public function toolbar(string $toolbar): self
    {
        return $this->withMeta(['toolbar' => $toolbar]);
    }

    public function plugins(string|array $plugins): self
    {
        if (is_array($plugins)) {
            $plugins = implode(' ', $plugins);
        }

        return $this->withMeta(['plugins' => $plugins]);
    }

    public function menubar(bool $menubar): self
    {
        return $this->withMeta(['menubar' => $menubar]);
    }

    public function statusbar(bool $statusbar): self
    {
        return $this->withMeta(['statusbar' => $statusbar]);
    }

    public function branding(bool $branding): self
    {
        return $this->withMeta(['branding' => $branding]);
    }

    public function resize(bool $resize): self
    {
        return $this->withMeta(['resize' => $resize]);
    }

    public function options(array $options): self
    {
        return $this->withMeta($options);
    }

    public function useLocal(): self
    {
        return $this->withMeta(array_intersect_key($this->getDefaultConfig('local'), array_flip(['source', 'base_url', 'script_path', 'license_key'])));
    }

    public function useCdn(): self
    {
        return $this->withMeta(array_intersect_key($this->getDefaultConfig('cdn'), array_flip(['source', 'base_url', 'api_key', 'version', 'license_key'])));
    }

    public function licenseKey(string $licenseKey): self
    {
        return $this->withMeta(['license_key' => $licenseKey]);
    }

    public function apiKey(string $apiKey): self
    {
        return $this->withMeta(['api_key' => $apiKey]);
    }

    public function baseUrl(string $baseUrl): self
    {
        return $this->withMeta(['base_url' => $baseUrl]);
    }

    public function scriptPath(string $scriptPath): self
    {
        return $this->withMeta(['script_path' => $scriptPath]);
    }

    public function version(string $version): self
    {
        return $this->withMeta(['version' => $version]);
    }

    /**
     * Resolve the field's value for display.
     */
    public function resolveForDisplay($resource, ?string $attribute = null): void
    {
        parent::resolveForDisplay($resource, $attribute);

        $withLineBreaks = preg_replace('/<\/?(?:br|p|div|li|ul|ol|tr|h[1-6]|blockquote)\b[^>]*>/i', "\n", (string) $this->value);
        $plainText = html_entity_decode(strip_tags($withLineBreaks), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $plainText = str_replace(["\r\n", "\r", "\u{00A0}"], ["\n", "\n", ' '], $plainText);
        $this->value = trim(preg_replace('/\n[\t ]*\n+/', "\n", $plainText));
    }
}
