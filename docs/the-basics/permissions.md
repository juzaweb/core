# Permission Manager

The `PermissionManager` singleton manages the registration and retrieval of permissions within the system. It replaces valid database storage for defining permissions, allowing them to be defined in code.

## Basic Usage

### Registering Permissions manually

You can register permissions using the `make` method. This is typically done in a ServiceProvider's `boot` method or via `AdminResource`.

```php
use Juzaweb\Modules\Core\Facades\PermissionManager;

PermissionManager::make(
    'users.create', 
    fn() => [
        'name' => 'Create users',
        'group' => 'users',
        'code' => 'users.create',
    ]
);
```

### Retrieving Permissions

To get all registered permissions:

```php
use Juzaweb\Modules\Core\Facades\PermissionManager;

$permissions = PermissionManager::getPermissions();
```
