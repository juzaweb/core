## Requirements

Before installing Juzaweb CMS, ensure the target system meets the minimum requirements:

-   PHP 8.2 or higher
-   Laravel 11.0 or higher
-   Mysql 5.7 or MariaDB 10.2 or higher
-   Composer 2.0 or higher
-   Node v20.11.0 or higher
-   NPM 10.2.4 or higher
-   PDO PHP Extension
-   cURL PHP Extension
-   OpenSSL PHP Extension
-   Mbstring PHP Extension
-   ZipArchive PHP Extension
-   GD PHP Extension
-   SimpleXML PHP Extension.

## Installation

Juzaweb CMS is a PHP web application that uses Composer to manage its dependencies. Ensure that Composer is installed before you begin. Ensure that Composer is installed before you begin.

### Create Project

To install the platform, initialize a project using the `create-project` command in the terminal.

```bash
composer create-project juzaweb/cms juzaweb
```

Go to project folder

```bash
cd juzaweb
```

Run the installation command:

```bash
php artisan juzaweb:install
```

### Publish config

```bash
php artisan vendor:publish --tag=juzaweb-config
```

### Publish Assets

```bash
php artisan vendor:publish --tag=juzaweb-assets
```

### Publish Views

```bash
php artisan vendor:publish --tag=juzaweb-views
```

### Publish Lang

```bash
php artisan vendor:publish --tag=juzaweb-lang
```
