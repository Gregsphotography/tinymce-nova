# Package Setup Guide

This guide will help you set up your TinyMCE Nova package for GitHub distribution.

## Prerequisites

- PHP 8.1+
- Composer
- Node.js 18+
- NPM
- Git
- GitHub account

## Initial Setup

### 1. Create GitHub Repository

1. Go to [GitHub](https://github.com) and create a new repository
2. Name it `tinymce-nova` (or your preferred name)
3. Make it public for open source distribution
4. Don't initialize with README (we already have one)

### 2. Initialize Git Repository

```bash
cd packages/greg/tinymce-nova
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/yourusername/tinymce-nova.git
git push -u origin main
```

### 3. Install Dependencies

```bash
composer install
npm install
```

### 4. Build Assets

```bash
npm run production
```

### 5. Run Tests

```bash
composer test
```

## Publishing to Packagist

### 1. Create Packagist Account

1. Go to [Packagist](https://packagist.org)
2. Sign up with your GitHub account
3. Submit your package URL: `https://github.com/yourusername/tinymce-nova`

### 2. Set Up GitHub Secrets

In your GitHub repository settings, add these secrets:

- `PACKAGIST_USERNAME`: Your Packagist username
- `PACKAGIST_TOKEN`: Your Packagist API token

### 3. Enable Auto-Update

In your Packagist package settings, enable "Auto-Update" and connect your GitHub repository.

## Release Process

### 1. Update Version

```bash
php scripts/release.php 1.0.0
```

### 2. Create Release

```bash
git add .
git commit -m "Release 1.0.0"
git tag v1.0.0
git push origin main --tags
```

### 3. Create GitHub Release

1. Go to your repository on GitHub
2. Click "Releases" → "Create a new release"
3. Choose tag `v1.0.0`
4. Add release notes
5. Click "Publish release"

The GitHub Actions workflow will automatically publish to Packagist.

## Local Development

### Testing in Your Main Project

1. Add the package to your main project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/greg/tinymce-nova"
        }
    ],
    "require": {
        "greg/tinymce-nova": "*"
    }
}
```

2. Run `composer update`

3. Update your Nova resources to use the new namespace:

```php
use Greg\TinymceNova\Tinymce;
```

## Maintenance

### Updating Dependencies

```bash
composer update
npm update
```

### Adding Features

1. Create a feature branch
2. Make your changes
3. Add tests
4. Update documentation
5. Submit a pull request

### Version Management

Use semantic versioning:
- `1.0.0` - Major release (breaking changes)
- `1.1.0` - Minor release (new features)
- `1.0.1` - Patch release (bug fixes)

## Troubleshooting

### Common Issues

1. **Assets not loading**: Make sure to run `npm run production`
2. **Tests failing**: Check PHP version compatibility
3. **Packagist not updating**: Verify GitHub Actions workflow is running

### Getting Help

- Check the [Issues](https://github.com/yourusername/tinymce-nova/issues) page
- Create a new issue with detailed information
- Include PHP version, Laravel version, and error messages

## Best Practices

1. **Always test before releasing**
2. **Keep dependencies up to date**
3. **Write comprehensive tests**
4. **Document all changes**
5. **Follow semantic versioning**
6. **Respond to issues promptly**
