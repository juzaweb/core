# Juzaweb Core

[![Test](https://github.com/juzaweb/core/workflows/Test/badge.svg)](https://github.com/juzaweb/core/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/juzaweb/core.svg?style=social)](https://packagist.org/packages/juzaweb/core)
[![GitHub Repo stars](https://img.shields.io/github/stars/juzaweb/core?style=social)](https://github.com/juzaweb/core)
[![GitHub followers](https://img.shields.io/github/followers/juzaweb?style=social)](https://github.com/juzaweb)

Juzaweb Core is the kernel of the Juzaweb CMS ecosystem, providing the essential building blocks for modular web applications. It handles the core logic for Modules, Themes, Hooks, Settings, and User Management, following a robust Facade-Contract-Repository architecture.

## Requirements

- PHP >= 8.2
- Laravel >= 11.0

## Documentation

- [Juzaweb CMS Documentation](https://juzaweb.com/documentation/juzaweb/core/5.x/getting-started/installation)

## Features

- **Modular Architecture**: Built-in support for modular development, allowing you to organize your application into independent modules.
- **Theme System**: Powerful theme management system with support for theme settings, widgets, and templates.
- **Hook System**: Extensible hook system (actions and filters) powered by `juzaweb/hooks` for plugin-like extensibility.
- **User Management**: Comprehensive role-based access control (RBAC) with user roles and permissions.
- **Media Manager**: Integrated file manager for handling uploads and media assets.
- **Settings API**: Global settings management with support for different storage drivers and caching.
- **Social Login**: Built-in support for social authentication (Google, Facebook, Twitter, Github, Instagram).
- **Security**: Secure by default with features like ReCaptcha validation and strict permission checks.

## Installation

Install the package via Composer:

```bash
composer require juzaweb/core
```

Publish the configuration files:

```bash
php artisan vendor:publish --tag=core-config
```

This will publish the following config files to your `config/` directory:
- `core.php`
- `media.php`
- `modules.php`
- `themes.php`
- `translator.php`

Publish the assets:

```bash
php artisan vendor:publish --tag=core-assets
```

## Architecture

This package follows a strict **Facade -> Contract -> Repository** pattern for its core components. This ensures loose coupling and makes the system highly testable and extensible.

Key components include:
- `GlobalData`: Central registry for global application data.
- `Setting`: Manages global settings.
- `Theme`: Handles theme registration and management.
- `Module`: Manages application modules.
- `Hook`: Manages actions and filters.

Most functionalities are exposed via Facades (e.g., `Juzaweb\Modules\Core\Facades\Theme`) which resolve to their respective Contracts and Repositories.

## Development

### Testing

Run the test suite:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

### Code Formatting

Format code using Laravel Pint:

```bash
composer format
```

Check code formatting without making changes:

```bash
composer format -- --test
```

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](https://github.com/juzaweb/cms/blob/master/CONTRIBUTING.md) for details.

## License

The Juzaweb Core package is open-sourced software licensed under the [GPL-2.0 license](LICENSE).
