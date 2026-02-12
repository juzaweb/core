#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# --- Default Values ---
PHP_VERSION="8.2"
INSTALL_NODE=false

# --- Parse Arguments ---
while [[ "$#" -gt 0 ]]; do
    case $1 in
        -p|--php) PHP_VERSION="$2"; shift ;;
        -npm) INSTALL_NODE=true ;;
        -h|--help) 
            echo "Usage: ./setup-env.sh [-p|--php version] [-npm]"
            echo "Example: ./setup-env.sh -p 8.3 -npm"
            exit 0 
            ;;
        *) echo "Unknown parameter passed: $1"; exit 1 ;;
    esac
    shift
done

echo "--- Configuration ---"
echo "PHP Version: $PHP_VERSION"
echo "Install Node: $INSTALL_NODE"
echo "----------------------"

echo "--- Updating system package list ---"
sudo apt-get update
sudo apt-get install -y software-properties-common curl zip unzip

echo "--- Adding PHP repository (Ondrej PPA) ---"
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update

echo "--- Installing PHP $PHP_VERSION and extensions ---"
sudo apt-get install -y "php$PHP_VERSION" "php$PHP_VERSION-cli" "php$PHP_VERSION-common" \
    "php$PHP_VERSION-curl" "php$PHP_VERSION-mbstring" "php$PHP_VERSION-xml" \
    "php$PHP_VERSION-zip" "php$PHP_VERSION-mysql" "php$PHP_VERSION-bcmath" \
    "php$PHP_VERSION-gd" "php$PHP_VERSION-imagick" "php$PHP_VERSION-sqlite3"

echo "--- Setting PHP $PHP_VERSION as default ---"
sudo update-alternatives --set php "/usr/bin/php$PHP_VERSION"

echo "--- Installing Composer ---"
if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
fi

# --- Node.js Logic ---
if [ "$INSTALL_NODE" = true ]; then
    echo "--- Installing Node.js (LTS) and npm 11.8.0 ---"
    curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
    sudo apt-get install -y nodejs
    sudo npm install -g npm@11.8.0
fi

echo "--- VERIFYING VERSIONS ---"
php -v
composer --version
[[ "$INSTALL_NODE" = true ]] && npm -v

echo "--- Installing Project Dependencies ---"
[ -f "composer.json" ] && composer install --no-interaction --prefer-dist
[[ "$INSTALL_NODE" = true && -f "package.json" ]] && npm install

echo "--- Setup Completed! ---"