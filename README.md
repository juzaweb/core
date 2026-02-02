# Juzaweb Core

[![Test](https://github.com/juzaweb/core/workflows/Test/badge.svg)](https://github.com/juzaweb/core/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/juzaweb/core.svg?style=social)](https://packagist.org/packages/juzaweb/core)
[![GitHub Repo stars](https://img.shields.io/github/stars/juzaweb/core?style=social)](https://github.com/juzaweb/core)
[![GitHub followers](https://img.shields.io/github/followers/juzaweb?style=social)](https://github.com/juzaweb)

Core package for Juzaweb CMS.

## Requirements

- PHP >= 8.2
- Laravel >= 11.0

## Features
- [x] File manager
- [x] Modules manager
- [x] Themes manager
- [x] Theme Widgets
- [x] Page blocks
- [ ] Upload themes and run composer require command from admin panel (show process bar)
- [ ] Upload Modules and run composer require command from admin panel (show process bar)
- [x] Social login
    - [x] Google
    - [x] Facebook
    - [x] Tweater
    - [x] Github
    - [x] Instagram
- [x] User Permission
  - [x] Role management
  - [x] Assign permissions to roles
  - [x] Assign roles to users
  - [x] User management
- [x] Media manager admin page
- [ ] Short Code
- [ ] Quick edit pages

## Installation

```bash
composer require juzaweb/core
```

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

When contributing to this package:

1. Write tests for new features
2. Ensure all tests pass: `composer test`
3. Format your code: `composer format`
4. Follow PSR-2 coding standards
5. Update documentation as needed

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
