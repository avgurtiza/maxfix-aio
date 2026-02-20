# GitHub Actions CI/CD Deployment Guide

This comprehensive guide will walk you through setting up automated testing and deployment for your MaxFix Laravel application using GitHub Actions, deploying via SSH to a DigitalOcean droplet.

## Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Architecture](#architecture)
4. [Step 1: Set Up DigitalOcean Droplet](#step-1-set-up-digitalocean-droplet)
5. [Step 2: Configure SSH Access](#step-2-configure-ssh-access)
6. [Step 3: Prepare the Server](#step-3-prepare-the-server)
7. [Step 4: Configure GitHub Secrets](#step-4-configure-github-secrets)
8. [Step 5: Push and Test](#step-5-push-and-test)
9. [Understanding the Workflows](#understanding-the-workflows)
10. [Troubleshooting](#troubleshooting)
11. [Security Best Practices](#security-best-practices)

---

## Overview

This CI/CD pipeline automatically:

1. **Tests** your code on every push/PR:
   - Runs PHPUnit tests for both Backend and Admin applications
   - Checks code quality with Laravel Pint (code style)
   - Performs static analysis with PHPStan
   - Builds frontend assets

2. **Deploys** to your server on push to `main`:
   - Creates backups before deployment
   - Deploys zero-downtime releases
   - Runs database migrations
   - Clears and optimizes caches
   - Restarts services
   - Sends notifications

---

## Prerequisites

Before starting, ensure you have:

- **GitHub Account** with repository access
- **DigitalOcean Account** with payment method configured
- **SSH Key Pair** (or create one during setup)
- **Domain Name** (optional, but recommended)
- **Basic understanding** of terminal commands

---

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        GitHub                                │
│  ┌─────────────────────────────────────────────────────────┐│
│  │                   Repository                             ││
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐   ││
│  │  │  test.yml    │  │ deploy.yml   │  │   Source    
│  │ │   ││  │  (Tests)     │  │  (Deploy)    │  │   Code       │   ││
│  │  └──────────────┘  └──────────────┘  └──────────────┘   ││
│  └─────────────────────────────────────────────────────────┘│
│                            │                                  │
│                            │ HTTPS                            │
│                            ▼                                  │
│  ┌─────────────────────────────────────────────────────────┐│
│  │                 GitHub Actions                           ││
│  │  • Run tests                                            ││
│  │  • Check code quality                                   ││
│  │  • Build assets                                        ││
│  │  • SSH to server                                       ││
│  │  • Deploy applications                                 ││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
                            │
                            │ SSH (with key)
                            ▼
┌─────────────────────────────────────────────────────────────┐
│              DigitalOcean Droplet (Ubuntu 22.04)             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  /var/www/maxfix/                                       ││
│  │  ├── backend/                                           ││
│  │  │   ├── current → releases/20240208-120000            ││
│  │  │   ├── releases/                                      ││
│  │  │   │   └── 20240208-120000/                          ││
│  │  │   └── backup-*/                                     ││
│  │  ├── admin/                                            ││
│  │  │   ├── current → releases/20240208-120000            ││
│  │  │   ├── docker-compose.yml                           ││
│  │  │   └── releases/                                    ││
│  │  └── .env (shared config)                             ││
│  └─────────────────────────────────────────────────────────┘│
│  ┌─────────────────────────────────────────────────────────┐│
│  │  Services:                                              ││
│  │  • Nginx (ports 80, 8080, 8081)                        ││
│  │  • PHP-FPM 8.2                                         ││
│  │  • PostgreSQL 15                                       ││
│  │  • Docker (for admin panel)                            ││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

---

## Step 1: Set Up DigitalOcean Droplet

### 1.1 Create the Droplet

1. **Log in** to [DigitalOcean](https://cloud.digitalocean.com)
2. Click **"Create"** → **"Droplets"**
3. **Choose an image**: Ubuntu 22.04 (LTS) x86_64
4. **Choose a plan**: Basic (for small apps, $4/mo is enough to start)
   - CPU: 1 vCPU
   - Memory: 1 GB
   - Storage: 25 GB SSD
5. **Choose a datacenter region**: Select one close to your users
6. **Authentication**: 
   - Select **"SSH Key"**
   - Click **"New SSH Key"**
   - If you don't have one, DigitalOcean can generate one for you
   - **Save the private key** to `~/.ssh/id_rsa_maxfix` on your computer
7. **Hostname**: `maxfix-production`
8. Click **"Create Droplet"**

### 1.2 Note Your Droplet IP

After creation, note your droplet's IP address (shown in the dashboard). It will look like: `123.456.78.90`

### 1.3 Configure Firewall

1. In DigitalOcean dashboard, go to **Networking** → **Firewalls**
2. Click **"Create Firewall"**
3. **Name**: `maxfix-firewall`
4. **Inbound Rules**:
   ```
   Type       | Port(s) | Source
   --------------------------------
   SSH        | 22      | 0.0.0.0/0 (or your IP only)
   HTTP       | 80      | 0.0.0.0/0
   HTTPS      | 443     | 0.0.0.0/0
   Custom TCP | 8080    | 0.0.0.0/0 (backend alt)
   Custom TCP | 8081    | 0.0.0.0/0 (admin alt)
   ```
5. **Outbound Rules**: Leave defaults
6. **Apply to**: Select your droplet
7. Click **"Create Firewall"**

---

## Step 2: Configure SSH Access

### 2.1 From Your Local Machine

```bash
# Navigate to your SSH directory
cd ~/.ssh

# If you generated a key with DigitalOcean, download it
# Otherwise, generate a new key pair
ssh-keygen -t ed25519 -C "github-actions@maxfix" -f id_ed25519_maxfix

# Start the SSH agent
eval "$(ssh-agent -s)"

# Add your private key to the agent
ssh-add ~/.ssh/id_ed25519_maxfix

# Display your public key (you'll need this for the server)
cat ~/.ssh/id_ed25519_maxfix.pub
```

### 2.2 Connect to Your Droplet

```bash
# Replace with your droplet IP
ssh root@123.456.78.90

# If you used a custom key:
ssh -i ~/.ssh/id_ed25519_maxfix root@123.456.78.90
```

### 2.3 Create Deploy User on Server

Once connected to your droplet as root:

```bash
# Create a deploy user
adduser deploy

# Add deploy user to sudo group
usermod -aG sudo deploy

# Create SSH directory for deploy user
mkdir -p /home/deploy/.ssh
chmod 700 /home/deploy/.ssh

# Create authorized_keys file
touch /home/deploy/.ssh/authorized_keys
chmod 600 /home/deploy/.ssh/authorized_keys

# Paste your public key (from id_ed25519_maxfix.pub)
echo "ssh-ed25519 AAAA... github-actions@maxfix" >> /home/deploy/.ssh/authorized_keys

# Set ownership
chown -R deploy:deploy /home/deploy/.ssh

# Test SSH as deploy user (from a new terminal)
ssh -i ~/.ssh/id_ed25519_maxfix deploy@123.456.78.90
```

### 2.4 Copy SSH Key for GitHub Actions

```bash
# Display the private key (you'll need this for GitHub Secrets)
cat ~/.ssh/id_ed25519_maxfix

# Copy the entire output including:
# -----BEGIN OPENSSH PRIVATE KEY-----
# ... (all the key content)
# -----END OPENSSH PRIVATE KEY-----
```

**Important**: This private key must be kept secret!

---

## Step 3: Prepare the Server

Connect to your droplet as root and run the following commands:

### 3.1 Update System

```bash
apt update && apt upgrade -y
```

### 3.2 Install Required Packages

```bash
# Install PHP and extensions
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php
apt update
apt install -y php8.2-fpm php8.2-cli php8.2-mbstring php8.2-xml php8.2-bcmath \
    php8.2-gd php8.2-pdo php8.2-pdo-sqlite php8.2-pgsql php8.2-curl \
    php8.2-zip php8.2-intl php8.2-redis

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Install Nginx
apt install -y nginx

# Install PostgreSQL
apt install -y postgresql postgresql-contrib

# Install Git
apt install -y git

# Install unzip
apt install -y unzip
```

### 3.3 Install Docker (for Admin Panel)

```bash
# Add Docker's official GPG key
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# Add Docker repository
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null

# Install Docker
apt update
apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# Add deploy user to docker group
usermod -aG docker deploy
```

### 3.4 Configure Nginx

```bash
# Create Nginx config for backend
cat > /etc/nginx/sites-available/maxfix-backend << 'EOF'
server {
    listen 80;
    server_name localhost;
    root /var/www/maxfix/backend/current/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Create Nginx config for admin (Docker will handle internal routing)
cat > /etc/nginx/sites-available/maxfix-admin << 'EOF'
server {
    listen 8081;
    server_name localhost;
    root /var/www/maxfix/admin/current/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Enable sites
ln -sf /etc/nginx/sites-available/maxfix-backend /etc/nginx/sites-enabled/
ln -sf /etc/nginx/sites-available/maxfix-admin /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test and reload Nginx
nginx -t
systemctl reload nginx
```

### 3.5 Configure PostgreSQL

```bash
# Switch to postgres user
su - postgres

# Create database and user
psql -c "CREATE DATABASE maxfix_admin;"
psql -c "CREATE USER maxfix_user WITH ENCRYPTED PASSWORD 'your_secure_password';"
psql -c "GRANT ALL PRIVILEGES ON DATABASE maxfix_admin TO maxfix_user;"
psql -c "ALTER USER maxfix_user WITH SUPERUSER;"
exit

# Update admin/.env on server with the password
```

### 3.6 Create Deployment Directory

```bash
# Create deployment directory
mkdir -p /var/www/maxfix/{backend,admin}/{releases,storage,public}

# Set permissions
chown -R deploy:deploy /var/www/maxfix
chmod -R 755 /var/www/maxfix
```

### 3.7 Create Environment Files on Server

```bash
# Create backend .env (as deploy user)
sudo -u deploy nano /var/www/maxfix/backend/.env
```

**Backend .env**:
```env
APP_NAME=MaxFix
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=http://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/maxfix/backend/database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

VITE_APP_NAME="${APP_NAME}"
```

```bash
# Create admin .env
sudo -u deploy nano /var/www/maxfix/admin/.env
```

**Admin .env**:
```env
APP_NAME="MaxFix Admin"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=http://localhost:8081

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=maxfix_admin
DB_USERNAME=maxfix_user
DB_PASSWORD=your_secure_password_from_postgres_setup

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

### 3.8 Initialize Backend Database

```bash
# Create SQLite database file
touch /var/www/maxfix/backend/database/database.sqlite
chown deploy:deploy /var/www/maxfix/backend/database/database.sqlite

# Generate app key
cd /var/www/maxfix/backend/current
sudo -u deploy php artisan key:generate --show

# Copy the key to .env file
```

---

## Step 4: Configure GitHub Secrets

### 4.1 Navigate to Repository Secrets

1. Go to your GitHub repository
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **"New repository secret"**

### 4.2 Add the Following Secrets

Create these secrets one by one:

| Secret Name | Value | Description |
|-------------|-------|-------------|
| `DO_SSH_PRIVATE_KEY` | (content of `~/.ssh/id_ed25519_maxfix`) | Private SSH key for connecting to droplet |
| `DO_SERVER_HOST` | `123.456.78.90` | Your droplet's IP address |
| `DO_SERVER_USER` | `deploy` | SSH username on server |
| `DO_DEPLOY_PATH` | `/var/www/maxfix` | Path where app is deployed |
| `DO_DB_ADMIN_PASSWORD` | (PostgreSQL password) | Password for admin DB user |
| `SLACK_WEBHOOK_URL` | (optional) | Slack incoming webhook for notifications |
| `DISCORD_WEBHOOK_ID` | (optional) | Discord webhook ID |
| `DISCORD_WEBHOOK_TOKEN` | (optional) | Discord webhook token |

### 4.3 Add Environment Variables (Optional but Recommended)

Click **"New repository secret"** for environment-specific variables:

| Secret Name | Backend Value | Admin Value |
|-------------|---------------|-------------|
| `BACKEND_APP_KEY` | (generated key) | N/A |
| `ADMIN_APP_KEY` | N/A | (generated key) |
| `ADMIN_DB_PASSWORD` | N/A | (PostgreSQL password) |

---

## Step 5: Push and Test

### 5.1 Commit the Workflow Files

```bash
# Check the status
git status

# Add the new files
git add .github/

# Commit
git commit -m "Add GitHub Actions CI/CD workflows"

# Push to main branch
git push origin main
```

### 5.2 Watch the Workflow Run

1. Go to your GitHub repository
2. Click **Actions** tab
3. You should see:
   - **Tests** workflow running automatically
   - Then **Deploy to Production** workflow (after tests pass)

### 5.3 Verify the Deployment

After deployment completes:

```bash
# SSH to your server
ssh -i ~/.ssh/id_ed25519_maxfix deploy@123.456.78.90

# Check the deployed files
ls -la /var/www/maxfix/backend/current/
ls -la /var/www/maxfix/admin/current/

# Check Nginx is running
curl http://localhost:8080
curl http://localhost:8081
```

---

## Understanding the Workflows

### test.yml Workflow

This workflow runs on every push and pull request to `main` or `develop`:

```yaml
# Jobs explained:
backend-tests     # Runs PHPUnit tests on backend with PHP 8.2 and 8.3
admin-tests       # Runs PHPUnit tests on admin panel
linting           # Checks code style with Laravel Pint and PHPStan
frontend-build    # Builds frontend assets with Vite
```

**How it works:**
1. Checks out your code
2. Sets up PHP with required extensions
3. Installs Composer dependencies
4. Copies `.env.example` to `.env`
5. Generates application keys
6. Runs database migrations
7. Executes tests with coverage reporting

### deploy.yml Workflow

This workflow runs automatically after `test.yml` succeeds on pushes to `main`:

```yaml
# Jobs explained:
test-deployment   # Verifies SSH connection works
deploy-backend     # Deploys the main application
deploy-admin       # Deploys the admin panel (depends on backend)
notify             # Sends Slack/Discord notifications
```

**Deployment Strategy:**
1. Creates a timestamped release directory
2. Extracts code from GitHub Actions artifact
3. Installs dependencies and builds assets
4. Runs database migrations
5. Clears and optimizes Laravel caches
6. Creates a symlink to current version
7. Restarts PHP-FPM/Docker services
8. Creates backups of previous releases

**Zero-Downtime Deployment:**
- New code goes to `releases/YYYYMMDD-HHMMSS/`
- Current symlink atomically switches to new release
- If deployment fails, rollback is easy: `ln -snf /var/www/maxfix/backend/releases/backup-name /var/www/maxfix/backend/current`

---

## Troubleshooting

### Common Issues and Solutions

#### 1. SSH Connection Refused

```bash
# Check SSH service on server
ssh deploy@123.456.78.90 "systemctl status ssh"

# Verify key permissions
chmod 600 ~/.ssh/id_ed25519_maxfix

# Check SSH config
nano ~/.ssh/config
```

**Add to `~/.ssh/config`:**
```
Host 123.456.78.90
    User deploy
    IdentityFile ~/.ssh/id_ed25519_maxfix
    StrictHostKeyChecking accept-new
```

#### 2. Permission Denied Errors

```bash
# On server, check directory permissions
ls -la /var/www/maxfix/

# Fix permissions
sudo chown -R deploy:deploy /var/www/maxfix
sudo chmod -R 755 /var/www/maxfix/backend/storage
sudo chmod -R 755 /var/www/maxfix/admin/storage
```

#### 3. Database Connection Failed

```bash
# On server, check PostgreSQL status
systemctl status postgresql

# Test connection
psql -U maxfix_user -d maxfix_admin -h 127.0.0.1

# Check .env file on server
cat /var/www/maxfix/admin/.env | grep DB_
```

#### 4. Nginx Returns 502 Bad Gateway

```bash
# Check PHP-FPM status
systemctl status php8.2-fpm

# Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl reload nginx

# Check logs
tail -f /var/log/nginx/error.log
tail -f /var/log/php8.2-fpm.log
```

#### 5. Artisan Commands Fail

```bash
# On server, navigate to current release
cd /var/www/maxfix/backend/current

# Clear all caches
php artisan optimize:clear

# Check for missing dependencies
composer install --no-dev

# Check .env file exists
cat .env

# Run migrations manually
php artisan migrate --force
```

#### 6. GitHub Actions Timeouts

If tests or deployment time out:
- Add caching for Composer and npm packages
- Reduce test coverage options
- Split jobs into smaller chunks

#### 7. Docker Container Won't Start

```bash
# On server, check Docker status
docker --version
docker-compose --version

# Check admin containers
cd /var/www/maxfix/admin/current
docker-compose logs

# Common fixes
docker-compose down
docker-compose up -d --build
```

### Viewing Logs

```bash
# GitHub Actions logs
# Go to: GitHub → Actions → Workflow run → View logs

# Server logs
ssh deploy@123.456.78.90
tail -f /var/www/maxfix/backend/storage/logs/laravel.log
tail -f /var/www/maxfix/admin/storage/logs/laravel.log

# Nginx logs
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
```

### Rollback Procedure

If deployment fails or breaks production:

```bash
# SSH to server
ssh deploy@123.456.78.90

# List available releases and backups
ls -la /var/www/maxfix/backend/releases/
ls -la /var/www/maxfix/backend/

# Rollback to previous release
ln -snf /var/www/maxfix/backend/releases/20240208-110000 /var/www/maxfix/backend/current

# Or rollback to backup
ln -snf /var/www/maxfix/backend/backup-20240208-100000 /var/www/maxfix/backend/current

# Restart services
sudo systemctl restart php8.2-fpm
```

---

## Security Best Practices

### 1. SSH Security

```bash
# On server, disable root login
sudo nano /etc/ssh/sshd_config

# Set:
PermitRootLogin no
PasswordAuthentication no
PubkeyAuthentication yes

# Restart SSH
sudo systemctl restart ssh
```

### 2. Firewall Configuration

```bash
# Allow only your IP for SSH
ufw allow from YOUR_IP_ADDRESS to any port 22
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable
```

### 3. Keep Software Updated

```bash
# Set up automatic security updates
sudo apt install unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades
```

### 4. Separate Environments

- Use different SSH keys for production and staging
- Create separate databases for each environment
- Use GitHub Environments for protection rules

### 5. GitHub Environment Protection

1. Go to **Settings** → **Environments**
2. Create `production` environment
3. Enable **"Required reviewers"** (optional)
4. Set **"Deployment branch rules"** to `main`
5. Enable **"Wait jobs before promoting"**

---

## Additional Resources

### Useful Commands

```bash
# Generate new application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret

# Run tests
php artisan test

# Run with coverage
php artisan test --coverage

# Check code style
./vendor/bin/pint --test

# Static analysis
./vendor/bin/phpstan analyse
```

### Quick Reference

| Task | Command |
|------|---------|
| Connect to server | `ssh -i ~/.ssh/id_ed25519_maxfix deploy@IP` |
| View deployed files | `ls -la /var/www/maxfix/backend/current/` |
| Check running services | `systemctl status nginx php8.2-fpm postgresql` |
| View Laravel logs | `tail -f /var/www/maxfix/backend/storage/logs/laravel.log` |
| Run artisan commands | `cd /var/www/maxfix/backend/current && php artisan` |
| Restart services | `sudo systemctl restart nginx php8.2-fpm` |
| Check disk usage | `df -h /var/www/maxfix/` |

### File Locations

| File | Location |
|------|----------|
| Backend code | `/var/www/maxfix/backend/current/` |
| Admin code | `/var/www/maxfix/admin/current/` |
| Backend .env | `/var/www/maxfix/backend/.env` |
| Admin .env | `/var/www/maxfix/admin/.env` |
| Nginx config | `/etc/nginx/sites-available/` |
| PHP-FPM config | `/etc/php/8.2/fpm/` |
| PostgreSQL data | `/var/lib/postgresql/` |
| Backups | `/var/www/maxfix/backend/backup-*` |

---

## Support

If you encounter issues:

1. **Check GitHub Actions logs** first
2. **Verify server logs** using SSH
3. **Test SSH connection** manually
4. **Check environment variables** match on server and GitHub
5. **Ensure all services** are running on the server

For additional help, review the troubleshooting section above or check the Laravel documentation at https://laravel.com/docs
