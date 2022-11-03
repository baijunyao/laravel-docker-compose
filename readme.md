# Laravel Docker Compose

Laravel Docker Compose provides a Docker powered local development experience for Laravel.

## Installation

Require this package with composer using the following command:
```bash
composer require baijunyao/laravel-docker-compose
```

## Usage

Publish the config and docker-compose.yml files
```bash
php artisan laravel-docker-compose:publish
```

## Set Up Docker For Mac with Native NFS
The default file system that Docker uses is quite slow in the Mac environment, for that reason, many dev’s used NFS.

Setup native NFS for Docker:
```bash
./vendor/baijunyao/laravel-docker-compose/bin/setup_native_nfs_docker_osx.sh
```

Use docker-compose-nfs.yml
```bash
docker compose -f docker-compose-nfs.yml up
```
