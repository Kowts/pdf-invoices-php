<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Bridge\Laravel;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Foundation\MaintenanceMode;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

final class LaravelApplication extends Container implements Application
{
    public function version(): string
    {
        return 'testing';
    }

    public function basePath($path = ''): string
    {
        return $this->path(sys_get_temp_dir(), $path);
    }

    public function bootstrapPath($path = ''): string
    {
        return $this->path(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bootstrap', $path);
    }

    public function configPath($path = ''): string
    {
        return $this->path(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'config', $path);
    }

    public function databasePath($path = ''): string
    {
        return $this->path(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'database', $path);
    }

    public function langPath($path = ''): string
    {
        return $this->path(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'lang', $path);
    }

    public function publicPath($path = ''): string
    {
        return $this->path(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'public', $path);
    }

    public function resourcePath($path = ''): string
    {
        return $this->path(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'resources', $path);
    }

    public function storagePath($path = ''): string
    {
        return $this->path(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'storage', $path);
    }

    /**
     * @param string|array<int, string> ...$environments
     */
    public function environment(...$environments): string|bool
    {
        if ($environments === []) {
            return 'testing';
        }

        return in_array('testing', $environments, true);
    }

    public function runningInConsole(): bool
    {
        return true;
    }

    public function runningUnitTests(): bool
    {
        return true;
    }

    public function hasDebugModeEnabled(): bool
    {
        return true;
    }

    public function maintenanceMode(): MaintenanceMode
    {
        return new LaravelMaintenanceMode();
    }

    public function isDownForMaintenance(): bool
    {
        return false;
    }

    public function registerConfiguredProviders(): void
    {
    }

    public function register($provider, $force = false): ServiceProvider
    {
        if ($provider instanceof ServiceProvider) {
            return $provider;
        }

        if (is_string($provider) && is_a($provider, ServiceProvider::class, true)) {
            return new $provider($this);
        }

        throw new RuntimeException('Unsupported provider.');
    }

    public function registerDeferredProvider($provider, $service = null): void
    {
    }

    public function resolveProvider($provider): ServiceProvider
    {
        if (is_string($provider) && is_a($provider, ServiceProvider::class, true)) {
            return new $provider($this);
        }

        throw new RuntimeException('Unsupported provider.');
    }

    public function boot(): void
    {
    }

    public function booting($callback): void
    {
    }

    public function booted($callback): void
    {
    }

    /**
     * @param array<int, class-string> $bootstrappers
     */
    public function bootstrapWith(array $bootstrappers): void
    {
    }

    public function getLocale(): string
    {
        return 'pt_PT';
    }

    public function getNamespace(): string
    {
        return 'App\\';
    }

    /**
     * @return array<int, ServiceProvider>
     */
    public function getProviders($provider): array
    {
        return [];
    }

    public function hasBeenBootstrapped(): bool
    {
        return true;
    }

    public function loadDeferredProviders(): void
    {
    }

    public function setLocale($locale): void
    {
    }

    public function shouldSkipMiddleware(): bool
    {
        return false;
    }

    public function terminating($callback): Application
    {
        return $this;
    }

    public function terminate(): void
    {
    }

    private function path(string $base, string $path): string
    {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        return rtrim($base, DIRECTORY_SEPARATOR) . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path);
    }
}

final class LaravelMaintenanceMode implements MaintenanceMode
{
    /**
     * @param array<string, mixed> $payload
     */
    public function activate(array $payload): void
    {
    }

    public function deactivate(): void
    {
    }

    public function active(): bool
    {
        return false;
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return [];
    }
}
