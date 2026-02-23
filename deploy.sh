#!/bin/bash

# Deploy script for MaxFix app
# Usage: ./deploy.sh

set -e

# Configuration
SERVER_HOST="${DO_SERVER_HOST}"
SERVER_USER="deploy"
SSH_KEY="$HOME/.ssh/id_deploy"
DEPLOY_PATH="/var/www/maxfix"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOCAL_APP="$SCRIPT_DIR/app"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}=== MaxFix Deployment Script ===${NC}"
echo ""

# Check if SSH key is set
if [ -z "$SERVER_HOST" ]; then
    echo -e "${RED}Error: SERVER_HOST not set${NC}"
    echo "Usage: DO_SERVER_HOST=your-droplet-ip ./deploy.sh"
    exit 1
fi

# Confirm deployment
echo "Server: $SERVER_HOST"
echo "Deploy path: $DEPLOY_PATH"
echo ""
read -p "Continue with deployment? (y/n) " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Aborted."
    exit 1
fi

# Get timestamp
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
RELEASE_PATH="$DEPLOY_PATH/releases/$TIMESTAMP"

echo -e "${YELLOW}=== Step 1: Building locally ===${NC}"

# Install composer deps
echo "Installing composer dependencies..."
cd "$LOCAL_APP"
composer install --no-dev --optimize-autoloader

# Install bun deps
echo "Installing bun dependencies..."
bun install

# Build assets
echo "Building assets..."
bun run build

echo -e "${GREEN}✓ Build complete${NC}"

# Create archive
echo -e "${YELLOW}=== Step 2: Creating deployment archive ===${NC}"
cd "$LOCAL_APP"
tar \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='.env' \
    --exclude='*.sqlite' \
    --exclude='storage/*.log' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/views/*' \
    -czf /tmp/app-deploy.tar.gz .

echo -e "${GREEN}✓ Archive created${NC}"

# Deploy via SSH
echo -e "${YELLOW}=== Step 3: Deploying to server ===${NC}"

# Transfer archive to server
echo "Transferring archive to server..."
scp -o StrictHostKeyChecking=no -i "$SSH_KEY" /tmp/app-deploy.tar.gz "$SERVER_USER@$SERVER_HOST:/tmp/app-deploy.tar.gz"

ssh -o StrictHostKeyChecking=no -i "$SSH_KEY" "$SERVER_USER@$SERVER_HOST" << EOF
set -e

DEPLOY_PATH="$DEPLOY_PATH"
RELEASE_PATH="$DEPLOY_PATH/releases/$TIMESTAMP"

echo "Creating release directory..."
mkdir -p "\$RELEASE_PATH"
mkdir -p /tmp

echo "Extracting archive..."
tar -xzf /tmp/app-deploy.tar.gz -C "\$RELEASE_PATH"

# Setup shared storage with proper structure
echo "Setting up shared storage..."
mkdir -p "$DEPLOY_PATH/shared/storage/logs"
mkdir -p "$DEPLOY_PATH/shared/storage/framework/views"
mkdir -p "$DEPLOY_PATH/shared/storage/framework/cache/data"
mkdir -p "$DEPLOY_PATH/shared/storage/framework/sessions"
mkdir -p "$DEPLOY_PATH/shared/storage/app/public"
mkdir -p "$DEPLOY_PATH/shared/storage/testing"

# Set permissions on shared storage
sudo chown -R www-data:www-data "$DEPLOY_PATH/shared/storage"
sudo chmod -R 775 "$DEPLOY_PATH/shared/storage"

# Create bootstrap cache
mkdir -p "$RELEASE_PATH/bootstrap/cache"
chmod -R 775 "$RELEASE_PATH/bootstrap/cache"

# Link storage directory
rm -rf "$RELEASE_PATH/storage"
ln -s "$DEPLOY_PATH/shared/storage" "$RELEASE_PATH/storage"

echo "Copying .env..."
if [ -f "$DEPLOY_PATH/shared/.env" ]; then
    cp "$DEPLOY_PATH/shared/.env" "\$RELEASE_PATH/.env"
elif [ -f "$DEPLOY_PATH/current/.env" ]; then
    cp "$DEPLOY_PATH/current/.env" "\$RELEASE_PATH/.env"
elif [ -f "\$RELEASE_PATH/.env.example" ]; then
    cp "\$RELEASE_PATH/.env.example" "\$RELEASE_PATH/.env"
fi

echo "Installing dependencies..."
cd "\$RELEASE_PATH"
COMPOSER_PROCESS_TIMEOUT=600 composer install --no-dev --no-scripts --no-interaction

echo "Running artisan commands..."
php artisan package:discover --no-interaction

echo "Running migrations..."
php artisan migrate --force

echo "Clearing caches..."
php artisan optimize:clear
php artisan optimize

echo "Setting permissions..."
sudo chown -R www-data:www-data "\$DEPLOY_PATH/releases/$TIMESTAMP"
sudo chown -R www-data:www-data "$DEPLOY_PATH/shared/storage"
sudo chmod -R 775 "$DEPLOY_PATH/shared/storage"
sudo chmod -R 775 "\$RELEASE_PATH/bootstrap/cache"

echo "Swapping symlink..."
rm -rf "$DEPLOY_PATH/current"
ln -s "\$RELEASE_PATH" "$DEPLOY_PATH/current"

echo "Restarting PHP-FPM..."
sudo systemctl restart php8.3-fpm || true

echo "Restarting Nginx..."
sudo systemctl restart nginx || true

echo "=== Deployment complete ==="
EOF

# Health check
echo -e "${YELLOW}=== Step 4: Health check ===${NC}"
sleep 3

if curl -sf -o /dev/null "http://$SERVER_HOST/health" 2>/dev/null; then
    echo -e "${GREEN}✓ Health check passed${NC}"
else
    echo -e "${YELLOW}⚠ Health endpoint not found, checking main page...${NC}"
    if curl -sf -o /dev/null "http://$SERVER_HOST/" 2>/dev/null; then
        echo -e "${GREEN}✓ Main page responding${NC}"
    else
        echo -e "${RED}✗ Health check failed${NC}"
    fi
fi

# Cleanup old releases
echo -e "${YELLOW}=== Step 5: Cleanup old releases ===${NC}"
ssh -o StrictHostKeyChecking=no -i "$SSH_KEY" "$SERVER_USER@$SERVER_HOST" << 'EOF'
DEPLOY_PATH="/var/www/maxfix"
KEEP_RELEASES=3

cd "$DEPLOY_PATH/releases"
RELEASE_COUNT=$(ls -1d 2>/dev/null | wc -l)

if [ "$RELEASE_COUNT" -gt "$KEEP_RELEASES" ]; then
    ls -1t | tail -n +$((KEEP_RELEASES + 1)) | xargs -r rm -rf
    echo "Cleaned up old releases"
else
    echo "No cleanup needed"
fi
EOF

echo ""
echo -e "${GREEN}=== Deployment successful! ===${NC}"
echo "Release: $TIMESTAMP"
echo "App: http://$SERVER_HOST"
