# Database (PostgreSQL)

## "Could not find driver" for PostgreSQL

This means PHP does not have the **pdo_pgsql** extension loaded.

### If you run the app with Docker

The image already installs `pdo_pgsql` in the Dockerfile. Rebuild so the extension is in the image:

```bash
docker compose build php
# or force a clean build:
docker compose build php --no-cache
```

Run Symfony console commands **inside** the PHP container so they use the same PHP (with the driver):

```bash
docker compose exec php php bin/console doctrine:migrations:migrate
docker compose exec php php bin/console doctrine:schema:validate
```

### If you run PHP on your host (e.g. `php bin/console`)

Install and enable the PostgreSQL PDO extension for your local PHP.

**macOS (Homebrew):**

```bash
# Install PostgreSQL client libraries if needed
brew install postgresql

# Enable pdo_pgsql in php.ini (path depends on your PHP)
# Find php.ini:
php --ini

# Uncomment or add:
# extension=pdo_pgsql
```

If your PHP was installed via Homebrew, the extension may be available but not enabled. Check:

```bash
php -m | grep -i pdo_pgsql
```

If missing, install the extension for your Homebrew PHP (formula name may vary, e.g. `php@8.3`):

```bash
pecl install pdo_pgsql
# Then add extension=pdo_pgsql to your php.ini
```

**Linux (Debian/Ubuntu):**

```bash
sudo apt-get install php-pdo-pgsql
# or for a specific PHP version:
sudo apt-get install php8.2-pdo-pgsql
```

**Linux (Fedora/RHEL):**

```bash
sudo dnf install php-pdo
sudo dnf install php-pgsql
```

After installing, restart the web server if you use one (e.g. `sudo systemctl restart php-fpm`).

### Quick check

```bash
php -r "var_dump(extension_loaded('pdo_pgsql'));"
# Should print: bool(true)
```
