# Themes

## Getting Started

### Make a Theme

```bash
php artisan theme:make ThemeName
```

## Theme Configuration

Themes can specify required modules in their `theme.json` file. When the theme is active, required modules are automatically loaded at runtime.

### Example `theme.json`

```json
{
    "name": "igame",
    "title": "Igame",
    "description": "Game store theme for managing and displaying games",
    "author": "Juzaweb",
    "version": "1.0",
    "providers": [
        "Juzaweb\\Themes\\Igame\\Providers\\ThemeServiceProvider"
    ],
    "require": [
        "GameStore"
    ]
}
```

The `require` field accepts an array of module names that will be booted when the theme is loaded. Required modules are registered and booted at runtime, similar to how the system boots enabled modules, but only when the theme is active.
