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

### Install with browser

1.  Open your browser and navigate to your project URL (e.g., `http://localhost:8000`).
2.  You will be redirected to the **Installer Wizard**.
3.  Follow the steps to configure your environment:
    *   **Requirements**: Check if your server meets the requirements.
    *   **Permissions**: Ensure directory permissions are correct.
    *   **Environment**: Configure database connection and app settings.
4.  Click **Install** to complete the setup.
5.  After installation, you can log in to the Admin Panel with the account you created.

### Publish config

```bash
php artisan vendor:publish --tag=core-config
```

### Publish Assets

```bash
php artisan vendor:publish --tag=core-assets
```

### Publish Views

```bash
php artisan vendor:publish --tag=core-views
```

### Publish Lang

```bash
php artisan vendor:publish --tag=core-lang
```
