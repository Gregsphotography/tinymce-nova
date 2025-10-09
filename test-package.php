<?php

declare(strict_types=1);

// Simple test to verify the package works
require_once __DIR__ . '/vendor/autoload.php';

use Greg\TinymceNova\Tinymce;

echo "Testing TinyMCE Nova Package...\n";

try {
    $field = Tinymce::make('Content');
    echo "✅ Package loaded successfully!\n";
    echo "Field component: " . $field->component . "\n";
    echo "Field name: " . $field->name . "\n";
    
    // Test configuration methods
    $field->height(500);
    $field->toolbar('undo redo | bold italic');
    $field->useLocal();
    
    echo "✅ Configuration methods work!\n";
    echo "Height: " . $field->meta['height'] . "\n";
    echo "Toolbar: " . $field->meta['toolbar'] . "\n";
    echo "Source: " . $field->meta['source'] . "\n";
    
    echo "\n🎉 Package is working correctly!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
