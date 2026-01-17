# Juzaweb Core

[![Test](https://github.com/juzaweb/core/workflows/Test/badge.svg)](https://github.com/juzaweb/core/actions)

Core package for Juzaweb CMS.

## Requirements

- PHP >= 8.2
- Laravel >= 11.0

## Installation

```bash
composer require juzaweb/core
```

## Development

### Testing

Run the test suite:

```bash
cd packages/core
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
