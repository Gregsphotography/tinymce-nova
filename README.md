# TinyMCE Field for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/greg/tinymce-nova.svg?style=flat-square)](https://packagist.org/packages/greg/tinymce-nova)
[![Total Downloads](https://img.shields.io/packagist/dt/greg/tinymce-nova.svg?style=flat-square)](https://packagist.org/packages/greg/tinymce-nova)
[![Tests](https://github.com/yourusername/tinymce-nova/workflows/Tests/badge.svg)](https://github.com/yourusername/tinymce-nova/actions)
[![License](https://img.shields.io/packagist/l/greg/tinymce-nova.svg?style=flat-square)](https://packagist.org/packages/greg/tinymce-nova)

A custom Laravel Nova field that integrates TinyMCE editor with your local TinyMCE installation.

## Features

- Uses local TinyMCE from `/public/tinymce/` directory
- Fully customizable toolbar and plugins
- Support for images, tables, code, and fullscreen editing
- No external CDN dependencies
- Dark mode compatible
- Paste handling with image support
- Laravel Package Tools integration
- Comprehensive test coverage

## Installation

You can install the package via Composer:

```bash
composer require greg/tinymce-nova
```

## Usage

### Basic Usage

```php
use Greg\TinymceNova\Tinymce;

Tinymce::make('Content')
    ->rules('required')
```

### With Configuration

```php
Tinymce::make('Content')
    ->rules('required')
    ->alwaysShow()
    ->height(500)
    ->plugins(['lists', 'link', 'image', 'paste', 'code', 'table', 'fullscreen', 'wordcount'])
    ->toolbar('undo redo | blocks | bold italic | link image')
```

## Available Methods

### `height(int $height)`
Set the editor height in pixels.

```php
Tinymce::make('Content')->height(600)
```

### `toolbar(string $toolbar)`
Customize the toolbar buttons.

```php
Tinymce::make('Content')->toolbar('undo redo | bold italic | link')
```

### `plugins(string|array $plugins)`
Set the TinyMCE plugins to use.

```php
Tinymce::make('Content')->plugins(['lists', 'link', 'image', 'code'])
```

### `menubar(bool $menubar)`
Show or hide the menubar.

```php
Tinymce::make('Content')->menubar(false)
```

### `statusbar(bool $statusbar)`
Show or hide the status bar.

```php
Tinymce::make('Content')->statusbar(false)
```

### `branding(bool $branding)`
Show or hide TinyMCE branding.

```php
Tinymce::make('Content')->branding(true)
```

### `resize(bool $resize)`
Enable or disable editor resizing.

```php
Tinymce::make('Content')->resize(false)
```

### `options(array $options)`
Pass custom TinyMCE configuration options.

```php
Tinymce::make('Content')->options([
    'content_css' => '/css/custom-editor.css',
    'body_class' => 'my-editor-class'
])
```

## Available Plugins

- `lists` - Bulleted and numbered lists
- `link` - Insert/edit links
- `image` - Insert/edit images
- `paste` - Paste from Word with formatting
- `code` - Edit HTML source code
- `table` - Insert/edit tables
- `fullscreen` - Fullscreen editing mode
- `wordcount` - Word and character count

## Default Configuration

```php
[
    'height' => 400,
    'toolbar' => 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code',
    'plugins' => 'lists link image code table',
    'menubar' => true,
    'statusbar' => true,
    'branding' => false,
    'resize' => true,
]
```

## Example: Article Resource

```php
<?php

declare(strict_types=1);

namespace App\Nova;

use Greg\TinymceNova\Tinymce;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

final class Article extends Resource
{
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Slug::make('Slug')
                ->from('Title')
                ->separator('-')
                ->rules('required', 'max:255')
                ->hideFromIndex(),

            Textarea::make('Excerpt')
                ->alwaysShow()
                ->rules('required', 'max:500'),

            Tinymce::make('Content')
                ->rules('required')
                ->alwaysShow()
                ->height(500)
                ->plugins(['lists', 'link', 'image', 'paste', 'code', 'table', 'fullscreen', 'wordcount']),

            Image::make('Image')
                ->disk('public')
                ->rules('required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'),
        ];
    }
}
```

## TinyMCE Installation

The field expects TinyMCE to be installed at:
```
/public/tinymce/js/tinymce/tinymce.min.js
```

### Installing TinyMCE

1. Download TinyMCE from [tinymce.com](https://www.tiny.cloud/get-tiny/self-hosted/)
2. Extract the files to your `public/tinymce/` directory
3. Ensure the structure is: `public/tinymce/js/tinymce/tinymce.min.js`

## Development

### Building Assets

If you make changes to the Vue components or JavaScript:

```bash
npm install
npm run production
```

### Testing

```bash
composer test
```

### Code Style

The package follows PSR-12 coding standards and uses PHPStan for static analysis.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email security@yourdomain.com instead of using the issue tracker.

## Credits

- [Greg](https://github.com/yourusername)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Tools

This package uses [Laravel Package Tools](https://github.com/spatie/laravel-package-tools) to provide a clean and consistent package development experience.
