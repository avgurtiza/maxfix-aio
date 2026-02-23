#!/bin/bash

# Rollback script for MaxFix app
# Usage: ./rollback.sh

set -e

# Configuration
SERVER_HOST="${DO_SERVER_HOST}"
SERVER_USER="deploy"
SSH_KEY="$HOME/.ssh/id_deploy"
DEPLOY_PATH="/var/www/maxfix"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}=== MaxFix Rollback Script ===${NC}"
echo ""

if [ -z "$SERVER_HOST" ]; then
    echo -e "${RED}Error: SERVER_HOST not set${NC}"
    echo "Usage: DO_SERVER_HOST=your-droplet-ip ./rollback.sh"
    exit 1
fi

# List available releases
echo "Available releases:"
ssh -i "$SSH_KEY" "$SERVER_USER@$SERVER_HOST" "ls -1t $DEPLOY_PATH/releases/"

echo ""
echo "Current symlink:"
ssh -i "$SSH_KEY" "$SERVER_USER@$SERVER_HOST" "ls -la $DEPLOY_PATH/current"

echo ""
read -p "Rollback to previous release? (y/n) " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Aborted."
    exit 1
fi

# Perform rollback
echo -e "${YELLOW}=== Rolling back ===${NC}"

ssh -o StrictHostKeyChecking=no -i "$SSH_KEY" "$SERVER_USER@$SERVER_HOST" << 'EOF'
set -e

DEPLOY_PATH="/var/www/maxfix"

# Find previous release (second newest)
PREVIOUS_RELEASE=$(ls -1t "$DEPLOY_PATH/releases" | sed -n '2p')

if [ -z "$PREVIOUS_RELEASE" ]; then
    echo "No previous release found!"
    exit 1
fi

echo "Rolling back to: $PREVIOUS_RELEASE"

# Re-symlink to previous release
rm -rf "$DEPLOY_PATH/current"
ln -s "$DEPLOY_PATH/releases/$PREVIOUS_RELEASE" "$DEPLOY_PATH/current"

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm || true

echo "Rollback complete!"
EOF

echo ""
echo -e "${GREEN}=== Rollback successful! ===${NC}"
