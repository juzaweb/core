# Juzaweb Core

[![Test](https://github.com/juzaweb/core/workflows/Test/badge.svg)](https://github.com/juzaweb/core/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/juzaweb/core.svg?style=social)](https://packagist.org/packages/juzaweb/core)
[![GitHub Repo stars](https://img.shields.io/github/stars/juzaweb/core?style=social)](https://github.com/juzaweb/core)
[![GitHub followers](https://img.shields.io/github/followers/juzaweb?style=social)](https://github.com/juzaweb)

Juzaweb Core is the kernel of the Juzaweb CMS ecosystem, providing the essential building blocks for modular web applications. It handles the core logic for Modules, Themes, Hooks, Settings, and User Management, following a robust Facade-Contract-Repository architecture.

## Requirements

- PHP >= 8.2
- Laravel >= 11.0

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

## Usage

### Registering Admin Menus

You can register new admin menu items using the `Menu` facade.

```php
use Juzaweb\Modules\Core\Facades\Menu;

Menu::make('my-plugin', function () {
    return [
        'title' => 'My Plugin',
        'icon' => 'fa fa-plug',
        'position' => 20,
        'parent' => null,
    ];
});
```

### Registering Sidebars

Register custom sidebars for your theme or module using the `Sidebar` facade.

```php
use Juzaweb\Modules\Core\Facades\Sidebar;

Sidebar::make('main_sidebar', function () {
    return [
        'label' => __('Main Sidebar'),
        'description' => __('The main sidebar for the theme.'),
    ];
});
```

### Registering Widgets

Register custom widgets that can be added to sidebars using the `Widget` facade.

```php
use Juzaweb\Modules\Core\Facades\Widget;

Widget::make('recent_posts', function () {
    return [
        'label' => __('Recent Posts'),
        'description' => __('Display recent posts.'),
        'view' => 'theme::widgets.recent_posts',
        'form' => 'theme::widgets.recent_posts_form',
    ];
});
```

## Blade Components

The package provides several Blade components for rapid development:

- `<x-card>`: A standard card container.
- `<x-form>`: Form wrapper.
- `<x-seo-meta>`: Renders SEO meta tags.
- `<x-js-var>`: Output PHP variables to JavaScript.
- `<x-theme-js-var>`: Output theme-specific JS variables.
- `<x-repeater>`: A repeater field component.
- `<x-language-card>`: A card component for multi-language content.
- `<x-cookie-consent>`: A cookie consent banner.

## Commands

The package includes several Artisan commands for maintenance and development:

- `juzaweb:clear-log`: Clear application log files.
- `juzaweb:make-user`: Create a new user via command line.
- `juzaweb:test-mail`: Send a test email to verify mail configuration.
- `juzaweb:cache-size`: Check the size of the cache.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](https://github.com/juzaweb/cms/blob/master/CONTRIBUTING.md) for details.

## License

The Juzaweb Core package is open-sourced software licensed under the [GPL-2.0 license](LICENSE).
