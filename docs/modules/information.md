Module is a package created to manage your large application using Modules. A Module is like a Laravel package, it has some views, controllers or models.

## Getting Started

### Make a Module

```bash
php artisan module:make Blog
```

### Folder Structure
```
├── config
│   └── blog.php
├── src
│   ├── Commands
│   ├── Database
│   │   ├── Factories
│   │   ├── Migrations
│   │   └── Seeders
│   ├── Http
│   │   ├── Controllers
│   │   ├── Middleware
│   │   └── Requests
│   ├── Models
│   ├── Providers
│   │   ├── BlogServiceProvider.php
│   │   └── RouteServiceProvider.php
│   ├── Repositories
│   ├── Resources
│   │   ├── js
│   │   │   └── app.js
│   │   ├── lang
│   │   ├── sass
│   │   │   └── app.scss
│   │   └── views
│   └── Routes
│       ├── api.php
│       └── web.php
├── tests
│   ├── Feature
│   └── Unit
├── assets
├── composer.json
├── module.json
```
