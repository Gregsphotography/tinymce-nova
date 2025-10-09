<?php

declare(strict_types=1);

namespace Greg\TinymceNova;

use Laravel\Nova\Fields\Expandable;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\SupportsDependentFields;

final class Tinymce extends Field
{
    use Expandable;
    use SupportsDependentFields;

    public $component = 'tinymce';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'height' => 400,
            'toolbar' => 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code',
            'plugins' => 'lists link image code table',
            'menubar' => true,
            'statusbar' => true,
            'branding' => false,
            'resize' => true,
        ]);
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
}
