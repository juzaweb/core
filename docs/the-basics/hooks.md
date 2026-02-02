# Hooks & Actions

Juzaweb CMS uses a hook system (powered by `juzaweb/hooks`) to allow modules and themes to interact with the core without modifying core files.

## Usage

Hooks are typically managed via the `HookAction` facade (if available) or by dependency injection of `Juzaweb\Hooks\Contracts\Hook`.

```php
use Juzaweb\Modules\Core\Support\Actions;

// Example of hooking into the menu initialization
public function add(): void
{
    $this->hook->addAction(Actions::MENU_INIT, [$this, 'register'], 20);
}
```

## Core Action Hooks

The `Juzaweb\Modules\Core\Support\Actions` class defines the constants for core actions.

### `Actions::MENU_INIT` (`menu.init`)
Triggered when the admin menu is being initialized. Use this hook to register new menu items.
