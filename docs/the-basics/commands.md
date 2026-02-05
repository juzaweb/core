# Artisan Commands

In addition to standard Laravel commands, Juzaweb CMS provides several custom Artisan commands to help with management and development.

## User Management

### `user:make`
Create a new user from the command line.

**Usage:**
```bash
php artisan user:make
```
**Options:**
- `--name=NAME`: The name of the user.
- `--email=EMAIL`: The email of the user.
- `--pass=PASSWORD`: The password of the user.
- `--super-admin`: Create a super admin user.
- `--role=ROLE_NAME`: Assign a role to the user immediately.

## System Maintenance

### `log:clear`
Clear all Laravel log files in `storage/logs`.

**Usage:**
```bash
php artisan log:clear
```

### `cache:size`
Show the current size of the file-based cache.

**Usage:**
```bash
php artisan cache:size
```

## Utilities

### `mail:test`
Send a test email to verify mail configuration.

**Usage:**
```bash
php artisan mail:test --email=admin@example.com
```

## Localization

### `language:make`
Create a new language. The language code must exist in the `locales` configuration.

**Usage:**
```bash
php artisan language:make en
```

**Arguments:**
- `code`: The code of the language (e.g., `en`, `vi`).
