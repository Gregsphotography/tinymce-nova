# Contributing

Thank you for considering contributing to TinyMCE Nova! Please read this guide before submitting any pull requests.

## Code Style

This package follows PSR-12 coding standards and uses PHPStan for static analysis.

### PHP

- Use `declare(strict_types=1);` at the top of every PHP file
- Follow PSR-12 coding standards
- Use explicit return types everywhere
- Prefer early returns over deep nesting
- Use `final` classes when appropriate
- Use constructor property promotion
- Prefer `match` for value selection

### JavaScript/Vue

- Follow Vue 3 Composition API patterns
- Use TypeScript when possible
- Follow ESLint rules
- Use meaningful variable names

## Testing

All new features and bug fixes must include tests.

### Running Tests

```bash
composer test
```

### Test Structure

- Unit tests go in `tests/Unit/`
- Feature tests go in `tests/Feature/`
- Use descriptive test method names
- Follow AAA pattern (Arrange, Act, Assert)

## Pull Request Process

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Add tests for your changes
5. Ensure all tests pass
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

## Reporting Issues

When reporting issues, please include:

- PHP version
- Laravel version
- Nova version
- Steps to reproduce
- Expected behavior
- Actual behavior
- Screenshots (if applicable)

## Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Install NPM dependencies: `npm install`
4. Build assets: `npm run production`
5. Run tests: `composer test`

## License

By contributing to this project, you agree that your contributions will be licensed under the MIT License.
