<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | TinyMCE Source Configuration
    |--------------------------------------------------------------------------
    |
    | Choose how TinyMCE is loaded in your application.
    | Options: 'local', 'cdn'
    |
    */
    'source' => env('TINYMCE_SOURCE', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Local TinyMCE Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for when using a local TinyMCE installation.
    |
    */
    'local' => [
        'base_url' => env('TINYMCE_LOCAL_URL', '/tinymce'),
        'script_path' => env('TINYMCE_SCRIPT_PATH', '/js/tinymce/tinymce.min.js'),
        'license_key' => env('TINYMCE_LICENSE_KEY', 'gpl'), // 'gpl' for GPL license, or your commercial key
    ],

    /*
    |--------------------------------------------------------------------------
    | CDN TinyMCE Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for when using TinyMCE from CDN.
    |
    */
    'cdn' => [
        'base_url' => env('TINYMCE_CDN_URL', 'https://cdn.tiny.cloud/1'),
        'api_key' => env('TINYMCE_API_KEY', 'no-api-key'),
        'license_key' => env('TINYMCE_LICENSE_KEY', 'gpl'), // 'gpl' for GPL license, or your commercial key
        'version' => env('TINYMCE_VERSION', '7'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Editor Configuration
    |--------------------------------------------------------------------------
    |
    | Default settings for the TinyMCE editor.
    | These can be overridden per field instance.
    |
    */
    'defaults' => [
        'height' => 400,
        'toolbar' => 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code',
        'plugins' => 'lists link image code table',
        'menubar' => true,
        'statusbar' => true,
        'branding' => false,
        'resize' => true,
        'content_css' => null,
        'body_class' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Advanced Configuration
    |--------------------------------------------------------------------------
    |
    | Additional TinyMCE configuration options.
    |
    */
    'advanced' => [
        'paste_data_images' => true,
        'paste_auto_cleanup_on_paste' => true,
        'paste_remove_styles_if_webkit' => true,
        'paste_merge_formats' => true,
        'paste_convert_word_fake_lists' => true,
        'paste_webkit_styles' => 'all',
        'paste_retain_style_properties' => 'color font-size font-family background-color',
        'paste_remove_empty_paragraphs' => true,
        'paste_remove_spans' => true,
        'paste_remove_styles' => false,
        'paste_remove_empty_paragraphs' => true,
        'paste_remove_spans' => true,
        'paste_remove_styles' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for image uploads in TinyMCE.
    |
    */
    'images' => [
        'upload_handler' => env('TINYMCE_IMAGE_HANDLER', 'laravel'),
        'upload_path' => env('TINYMCE_UPLOAD_PATH', 'storage/tinymce'),
        'max_file_size' => env('TINYMCE_MAX_FILE_SIZE', '2MB'),
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Security settings for TinyMCE content.
    |
    */
    'security' => [
        'valid_elements' => null, // Set to null to use TinyMCE defaults
        'invalid_elements' => 'script,object,embed,form,input,textarea,button,select,option',
        'extended_valid_elements' => null,
        'custom_elements' => null,
        'verify_html' => true,
        'cleanup' => true,
        'cleanup_on_startup' => true,
    ],
];
