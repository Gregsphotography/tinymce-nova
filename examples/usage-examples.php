<?php

declare(strict_types=1);

/**
 * TinyMCE Nova Usage Examples
 * 
 * These examples show different ways to use the TinyMCE field
 * with various configuration options.
 */

use Greg\TinymceNova\Tinymce;

// Basic usage with default configuration
Tinymce::make('Content')
    ->rules('required');

// Using local TinyMCE installation
Tinymce::make('Content')
    ->useLocal()
    ->baseUrl('/tinymce')
    ->scriptPath('/js/tinymce/tinymce.min.js')
    ->licenseKey('gpl');

// Using CDN with API key
Tinymce::make('Content')
    ->useCdn()
    ->apiKey('your-api-key')
    ->licenseKey('your-commercial-license')
    ->version('7');

// Custom configuration
Tinymce::make('Content')
    ->height(600)
    ->toolbar('undo redo | bold italic | link image | code')
    ->plugins(['lists', 'link', 'image', 'code'])
    ->menubar(false)
    ->statusbar(false)
    ->branding(true);

// Advanced configuration with custom options
Tinymce::make('Content')
    ->height(500)
    ->plugins(['lists', 'link', 'image', 'paste', 'code', 'table', 'fullscreen', 'wordcount'])
    ->toolbar('undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code')
    ->options([
        'content_css' => '/css/custom-editor.css',
        'body_class' => 'my-editor-class',
        'paste_data_images' => true,
        'paste_auto_cleanup_on_paste' => true,
    ]);

// Using with different license types
Tinymce::make('Content')
    ->licenseKey('gpl'); // For GPL license

Tinymce::make('Content')
    ->licenseKey('your-commercial-license-key'); // For commercial license

// Environment-based configuration
// Set in your .env file:
// TINYMCE_SOURCE=local
// TINYMCE_LOCAL_URL=/tinymce
// TINYMCE_LICENSE_KEY=gpl

// Then use without additional configuration:
Tinymce::make('Content'); // Will use environment settings
