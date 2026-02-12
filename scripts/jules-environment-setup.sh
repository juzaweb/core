#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

echo "--- Updating system package list ---"
sudo apt-get update

echo "--- Installing common dependencies ---"
sudo apt-get install -y software-properties-common curl zip unzip

echo "--- Adding PHP repository (Ondrej PPA) ---"
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update

echo "--- Installing PHP 8.2 and required extensions ---"
sudo apt-get install -y php8.2 php8.2-cli php8.2-common php8.2-curl php8.2-mbstring \
    php8.2-xml php8.2-zip php8.2-mysql php8.2-bcmath php8.2-gd php8.2-imagick php8.2-sqlite3

echo "--- Forcing PHP 8.2 as the default version ---"
# This prevents the system from automatically switching to PHP 8.3
sudo update-alternatives --set php /usr/bin/php8.2

echo "--- Installing Composer ---"
if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
fi

echo "--- Installing Node.js (LTS) and npm 11.8.0 ---"
# Node.js 20.x is recommended for npm 11 compatibility
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# Upgrade npm to the specific version 11.8.0 globally
sudo npm install -g npm@11.8.0

echo "--- VERIFYING VERSIONS ---"
php -v
composer --version
node -v
npm -v

echo "--- Installing Project Dependencies ---"
# Install PHP dependencies if composer.json exists
if [ -f "composer.json" ]; then
    echo "Running composer install..."
    composer install --no-interaction --prefer-dist
fi

# Install Node dependencies if package.json exists
if [ -f "package.json" ]; then
    echo "Running npm install..."
    npm install
fi

echo "--- Setup Completed Successfully! ---"