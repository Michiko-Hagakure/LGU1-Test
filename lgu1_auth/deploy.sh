#!/bin/bash

# Deployment script for LGU1-LOGIN
set -e

echo "Starting deployment..."

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run tests
echo "Running tests..."
php test/test_registration.php
php test/test_login_redirect.php

# Create backup
echo "Creating backup..."
timestamp=$(date +%Y%m%d_%H%M%S)
mkdir -p backups
tar -czf "backups/backup_$timestamp.tar.gz" --exclude='backups' --exclude='.git' .

# Set proper permissions
echo "Setting permissions..."
find . -type f -name "*.php" -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod 777 uploads/id_images/

echo "Deployment completed successfully!"