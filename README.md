# TinyMCE Field for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gregsphotography/tinymce-nova.svg?style=flat-square)](https://packagist.org/packages/gregsphotography/tinymce-nova)
[![Total Downloads](https://img.shields.io/packagist/dt/gregsphotography/tinymce-nova.svg?style=flat-square)](https://packagist.org/packages/gregsphotography/tinymce-nova)
[![Tests](https://github.com/gregsphotography/tinymce-nova/workflows/Tests/badge.svg)](https://github.com/gregsphotography/tinymce-nova/actions)
[![License](https://img.shields.io/packagist/l/gregsphotography/tinymce-nova.svg?style=flat-square)](https://packagist.org/packages/gregsphotography/tinymce-nova)

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
composer require gregsphotography/tinymce-nova
```

### Publish Configuration

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Greg\TinymceNova\TinymceNovaServiceProvider" --tag="config"
```

This will create `config/tinymce-nova.php` with all available configuration options.

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# TinyMCE Source (local or cdn)
TINYMCE_SOURCE=local

# For Local Installation
TINYMCE_LOCAL_URL=/tinymce
TINYMCE_SCRIPT_PATH=/js/tinymce/tinymce.min.js
TINYMCE_LICENSE_KEY=gpl

# For CDN Installation
TINYMCE_CDN_URL=https://cdn.tiny.cloud/1
TINYMCE_API_KEY=your-api-key-here
TINYMCE_VERSION=7
```

### Configuration File

The published `config/tinymce-nova.php` file contains all available options:

```php
return [
    'source' => env('TINYMCE_SOURCE', 'local'),
    
    'local' => [
        'base_url' => env('TINYMCE_LOCAL_URL', '/tinymce'),
        'script_path' => env('TINYMCE_SCRIPT_PATH', '/js/tinymce/tinymce.min.js'),
        'license_key' => env('TINYMCE_LICENSE_KEY', 'gpl'),
    ],
    
    'cdn' => [
        'base_url' => env('TINYMCE_CDN_URL', 'https://cdn.tiny.cloud/1'),
        'api_key' => env('TINYMCE_API_KEY', 'no-api-key'),
        'license_key' => env('TINYMCE_LICENSE_KEY', 'gpl'),
        'version' => env('TINYMCE_VERSION', '7'),
    ],
    
    'defaults' => [
        'height' => 400,
        'toolbar' => 'undo redo | blocks | bold italic...',
        'plugins' => 'lists link image code table',
        // ... more options
    ],
];
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

### Using CDN

```php
Tinymce::make('Content')
    ->useCdn()
    ->apiKey('your-api-key')
    ->licenseKey('your-license-key')
```

### Using Local Installation

```php
Tinymce::make('Content')
    ->useLocal()
    ->baseUrl('/tinymce')
    ->scriptPath('/js/tinymce/tinymce.min.js')
    ->licenseKey('gpl') // or your commercial license
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

### `useLocal()`
Force the field to use local TinyMCE installation.

```php
Tinymce::make('Content')->useLocal()
```

### `useCdn()`
Force the field to use TinyMCE from CDN.

```php
Tinymce::make('Content')->useCdn()
```

### `licenseKey(string $licenseKey)`
Set the TinyMCE license key (GPL or commercial).

```php
Tinymce::make('Content')->licenseKey('your-license-key')
```

### `apiKey(string $apiKey)`
Set the TinyMCE API key for CDN usage.

```php
Tinymce::make('Content')->apiKey('your-api-key')
```

### `baseUrl(string $baseUrl)`
Set the base URL for TinyMCE (local or CDN).

```php
Tinymce::make('Content')->baseUrl('/tinymce')
```

### `scriptPath(string $scriptPath)`
Set the script path for local TinyMCE installation.

```php
Tinymce::make('Content')->scriptPath('/js/tinymce/tinymce.min.js')
```

### `version(string $version)`
Set the TinyMCE version for CDN usage.

```php
Tinymce::make('Content')->version('7')
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

If you discover any security related issues, please email security@gbrown.ch instead of using the issue tracker.

## Credits

- [Greg](https://github.com/gregsphotography)
- [All Contributors](https://github.com/gregsphotography/tinymce-nova/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Tools

This package uses [Laravel Package Tools](https://github.com/spatie/laravel-package-tools) to provide a clean and consistent package development experience.
