**Juzaweb** or **Juzaweb CMS** is a modular, extensible **content management system (CMS)** built with **Laravel 11+, PHP 8.2, MySQL, AdminLTE, and Bootstrap 4**.
It allows users to **create and manage different types of websites** — such as blogs, news portals, movie or video sharing platforms, and story reading sites — with both **free and paid** options.

# Tech Stack

* **Backend:** Laravel 11+, PHP 8.2
* **Database:** MySQL
* **Frontend:** AdminLTE, Bootstrap 4
* **Others:** Composer, NPM, Webpack/Mix

# Install

* Install composer and migration

```
composer install
php artisan app:install
```

* Set `.env`
```
NETWORK_DOMAIN=domain.test
NETWORK_SUBSITE_DOMAIN=domain.test
```

* Set database, websites table column domain = domain.test

* Active theme for Main site, run command
```
php artisan theme:active Main
```
