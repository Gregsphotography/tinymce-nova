<?php

declare(strict_types=1);

/**
 * Release script for TinyMCE Nova package
 * 
 * Usage: php scripts/release.php [version]
 * Example: php scripts/release.php 1.0.0
 */

if ($argc < 2) {
    echo "Usage: php scripts/release.php [version]\n";
    echo "Example: php scripts/release.php 1.0.0\n";
    exit(1);
}

$version = $argv[1];

if (!preg_match('/^\d+\.\d+\.\d+$/', $version)) {
    echo "Error: Version must be in format x.y.z (e.g., 1.0.0)\n";
    exit(1);
}

echo "Releasing version {$version}...\n";

// Update composer.json version
$composerPath = __DIR__ . '/../composer.json';
$composer = json_decode(file_get_contents($composerPath), true);
$composer['version'] = $version;
file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Update package.json version
$packagePath = __DIR__ . '/../package.json';
$package = json_decode(file_get_contents($packagePath), true);
$package['version'] = $version;
file_put_contents($packagePath, json_encode($package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "✅ Updated version to {$version} in composer.json and package.json\n";

// Build assets
echo "Building assets...\n";
exec('npm run production', $output, $returnCode);

if ($returnCode !== 0) {
    echo "❌ Failed to build assets\n";
    exit(1);
}

echo "✅ Assets built successfully\n";

// Run tests
echo "Running tests...\n";
exec('composer test', $output, $returnCode);

if ($returnCode !== 0) {
    echo "❌ Tests failed\n";
    exit(1);
}

echo "✅ All tests passed\n";

echo "\n🎉 Release {$version} is ready!\n";
echo "\nNext steps:\n";
echo "1. Commit your changes: git add . && git commit -m 'Release {$version}'\n";
echo "2. Create a tag: git tag v{$version}\n";
echo "3. Push to GitHub: git push origin main --tags\n";
echo "4. Create a GitHub release with the tag v{$version}\n";
echo "\nThe GitHub Actions workflow will automatically publish to Packagist.\n";
