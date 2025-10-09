# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 2025-10-09

### Added
- **Multiple TinyMCE instances support** - Fixed issue where only one TinyMCE field would load per page
- Unique ID generation for each editor instance
- Proper editor initialization with conflict prevention
- Enhanced error handling and debugging

### Fixed
- Multiple TinyMCE fields now work correctly on the same page
- Editor initialization conflicts resolved
- Improved DOM element checking before initialization
- Better cleanup when components are destroyed

## [1.0.0] - 2025-10-09

### Added
- Initial release
- TinyMCE field for Laravel Nova
- Support for local TinyMCE installation
- Customizable toolbar and plugins
- Image, table, and code editing support
- Dark mode compatibility
- Paste handling with image support
- Comprehensive test coverage
- Laravel Package Tools integration
