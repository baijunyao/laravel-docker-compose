<?php

declare(strict_types=1);

namespace Baijunyao\LaravelDockerCompose\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-docker-compose:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the docker compose files';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (File::exists($this->laravel->basePath('config/laravel-docker-compose.php')) === false) {
            $this->call('vendor:publish', ['--tag' => 'laravel-docker-compose']);
        }

        if (File::exists($this->laravel->basePath('deploy/docker/nginx/default.conf'))) {
            if ($this->confirm('The deploy/docker/nginx/default.conf already exists. Do you want to overwrite it?')) {
                $this->publishNginxConfig();
            }
        } else {
            $this->publishNginxConfig();
        }

        $version_config = Config::get('laravel-docker-compose');

        if (File::exists($this->laravel->basePath('docker-compose.yml'))) {
            if ($this->confirm('A The docker-compose.yml already exists. Do you want to overwrite it?')) {
                $this->publishDockerCompose($version_config);
            }
        } else {
            $this->publishDockerCompose($version_config);
        }

        if (File::exists($this->laravel->basePath('docker-compose-nfs.yml'))) {
            if ($this->confirm('A The docker-compose-nfs already exists. Do you want to overwrite it?')) {
                $this->publishDockerComposeNfs($version_config);
            }
        } else {
            $this->publishDockerComposeNfs($version_config);
        }
    }

    public function publishNginxConfig(): void
    {
        if (File::isDirectory($this->laravel->basePath('deploy/docker/nginx')) === false) {
            File::makeDirectory($this->laravel->basePath('deploy/docker/nginx'), 0755, true);
        }

        File::put(
            $this->laravel->basePath('deploy/docker/nginx/default.conf'),
            str_replace(
                [
                    '{{server_name}}',
                ],
                [
                    parse_url(Config::get('app.url'), PHP_URL_HOST),
                ],
                File::get(__DIR__ . '/../../stubs/nginx.stub')
            )
        );
    }

    public function publishDockerCompose(array $version_config): void
    {
        $database_config = Config::get('database.connections.mysql');

        File::put(
            $this->laravel->basePath('docker-compose.yml'),
            str_replace(
                [
                    '{{nginx_version}}',
                    '{{php_version}}',
                    '{{mysql_version}}',
                    '{{elasticsearch_version}}',
                    '{{redis_version}}',
                    '{{mysql_user}}',
                    '{{mysql_database}}',
                    '{{mysql_password}}',
                ],
                [
                    $version_config['nginx_version'],
                    $version_config['php_version'],
                    $version_config['mysql_version'],
                    $version_config['elasticsearch_version'],
                    $version_config['redis_version'],
                    $database_config['username'],
                    $database_config['database'],
                    $database_config['password'] ?? '',
                ],
                File::get(__DIR__ . '/../../stubs/docker-compose.stub')
            )
        );
    }

    public function publishDockerComposeNfs(array $version_config): void
    {
        File::put(
            $this->laravel->basePath('docker-compose-nfs.yml'),
            str_replace(
                [
                    '{{nginx_version}}',
                    '{{php_version}}',
                ],
                [
                    $version_config['nginx_version'],
                    $version_config['php_version'],
                ],
                File::get(__DIR__ . '/../../stubs/docker-compose-nfs.stub')
            )
        );
    }
}
