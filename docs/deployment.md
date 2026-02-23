# Deployment Guide

## Overview

Manual-triggered, zero-downtime deployment for Laravel + Livewire application to DigitalOcean droplet using local shell scripts with SSH.

## Architecture

```
Local Machine (deploy.sh) → SSH to Droplet → Zero-Downtime Deploy
                                               ↓
                                        Atomic Symlink Swap
                                               ↓
                                        Health Check
                                               ↓
                                        Rollback on Failure
```

## Directory Structure

### Local

```
max-fix/
├── app/                 → Laravel + Livewire application
├── admin/               → Filament admin panel
├── deploy.sh            → Deployment script
├── rollback.sh          → Rollback script
└── docs/
    └── deployment.md    → This file
```

### Server

```
/var/www/maxfix/
├── current/             → symlink to latest release (Nginx doc root points here)
├── releases/
│   ├── 20260221-114747/
│   ├── 20260220-120000/
│   └── ...
└── shared/
    ├── .env             → production environment file
    └── storage/         → persisted storage directory (symlinked to releases)
        ├── logs/
        ├── framework/
        │   ├── views/
        │   ├── cache/data/
        │   └── sessions/
        ├── app/public/
        └── testing/
```

---

## Server Setup (One-time)

### 1. Create Deploy User

```bash
# SSH as root
ssh root@your-droplet-ip

# Create deploy user
sudo adduser deploy
sudo usermod -aG www-data deploy
```

### 2. Create Deployment Directories

```bash
sudo mkdir -p /var/www/maxfix/{releases,shared/storage}
sudo chown -R deploy:www-data /var/www/maxfix
```

### 3. Create Shared Storage Structure

```bash
sudo mkdir -p /var/www/maxfix/shared/storage/{logs,framework/views,framework/cache/data,framework/sessions,app/public,testing}
sudo chown -R www-data:www-data /var/www/maxfix/shared/storage
sudo chmod -R 775 /var/www/maxfix/shared/storage
```

### 4. Configure Sudo Permissions

```bash
sudo visudo
```

Add this line:
```
deploy ALL=(ALL) NOPASSWD: /bin/systemctl restart php8.3-fpm, /bin/systemctl restart nginx, /bin/chown, /bin/chmod
```

### 5. Add SSH Key

On your local machine:
```bash
ssh-copy-id -i ~/.ssh/id_deploy deploy@your-droplet-ip
```

Or manually on the server:
```bash
sudo mkdir -p /home/deploy/.ssh
sudo chmod 700 /home/deploy/.ssh
sudo nano /home/deploy/.ssh/authorized_keys
# Paste your public key
sudo chmod 600 /home/deploy/.ssh/authorized_keys
sudo chown -R deploy:deploy /home/deploy/.ssh
```

### 6. Create Production .env

```bash
sudo nano /var/www/maxfix/shared/.env
```

Example:
```env
APP_NAME=MaxFix
APP_ENV=production
APP_KEY=base64:L80zD6F5pEo5DwKwJvgptZk7u+yoAKE+zLKaYLFSp1I=
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=maxfix
DB_USERNAME=maxfix_user
DB_PASSWORD=your_db_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
```

Generate a new APP_KEY:
```bash
php artisan key:generate --show
```

### 7. Nginx Configuration

Ensure your Nginx config points to:
```nginx
root /var/www/maxfix/current/public;
```

---

## Deployment

### Deploy

```bash
./deploy.sh
```

The script will:
1. Install composer dependencies locally
2. Install bun dependencies locally
3. Build frontend assets locally (Livewire/Vite)
4. Create deployment archive
5. Transfer archive to server via SCP
6. Extract to new release directory
7. Setup shared storage with proper structure
8. Link storage directory to shared storage
9. Copy .env from shared storage
10. Run composer install on server
11. Run artisan package:discover
12. Run database migrations
13. Clear and optimize caches
14. Set permissions
15. Atomic symlink swap (zero-downtime)
16. Restart PHP-FPM
17. Health check
18. Cleanup old releases (keep 3)

### Rollback

```bash
./rollback.sh
```

Lists available releases and reverts to the previous one.

---

## Rollback Strategy

- Last 3 releases are kept in `/var/www/maxfix/releases/`
- Manual rollback via `./rollback.sh`

---

## Troubleshooting

### SSH Permission Denied

```bash
# Verify SSH key
ssh -i ~/.ssh/id_deploy deploy@your-droplet-ip

# Check key permissions
chmod 600 ~/.ssh/id_deploy
```

### Storage Permission Denied

```bash
# Fix storage permissions
sudo chown -R www-data:www-data /var/www/maxfix/shared/storage
sudo chmod -R 775 /var/www/maxfix/shared/storage
```

### Database Migration Errors

Check `.env` database credentials:
```bash
cat /var/www/maxfix/shared/.env | grep DB_
```

### View Logs

```bash
# Laravel logs
tail -f /var/www/maxfix/current/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/error.log

# PHP-FPM logs
tail -f /var/log/php8.3-fpm/error.log
```

### Manual Symlink Fix

```bash
ssh deploy@your-droplet-ip
rm -rf /var/www/maxfix/current
ln -s /var/www/maxfix/releases/YOUR_RELEASE /var/www/maxfix/current
sudo systemctl restart php8.3-fpm
```

---

## Files

| File | Purpose |
|------|---------|
| `deploy.sh` | Deploy to production |
| `rollback.sh` | Revert to previous release |
| `docs/deployment.md` | This documentation |
