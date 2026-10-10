# Laravel Docker Compose

Laravel Docker Compose provides a Docker powered local development experience for Laravel.

## Installation

Require this package with composer using the following command:
```bash
composer require baijunyao/laravel-docker-compose --dev
```

## Usage

Publish the config, docker-compose.yml and nginx config files
```bash
php artisan laravel-docker-compose:publish
```

This will generate:

- `config/laravel-docker-compose.php` — nginx / php image versions
- `deploy/docker/nginx/default.conf` — nginx virtual host (server name comes from `APP_URL`)
- `docker-compose.yml` — nginx + php services, connected to the external `traefik` network

Container names are prefixed with the project directory name (e.g. `msh-nginx`, `msh-php`).
MySQL and Redis are expected to run on the host machine and are accessed via `docker.for.mac.host.internal`.
