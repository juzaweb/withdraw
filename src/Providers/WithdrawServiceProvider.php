<?php

namespace Juzaweb\Modules\Withdraw\Providers;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Facades\Menu;
use Juzaweb\Modules\Core\Providers\ServiceProvider;

class WithdrawServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
        $this->booted(
            function () {
                $this->registerMenus();
            }
        );
    }

    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../../Database/migrations');
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerMenus(): void
    {
        if (File::missing(storage_path('app/installed'))) {
            return;
        }

        Menu::make('withdraws-management', function () {
            return [
                'title' => __('Withdraws'),
                'priority' => 50,
                'icon' => 'fas fa-money-bill-wave',
            ];
        });

        Menu::make('withdraws', function () {
            return [
                'title' => __('Withdraws'),
                'parent' => 'withdraws-management',
                'priority' => 99,
                'permissions' => ['withdraws.index'],
            ];
        });

        Menu::make('withdraw-methods', function () {
            return [
                'title' => __('Withdraw Methods'),
                'parent' => 'withdraws-management',
                'priority' => 99,
                'permissions' => ['withdraw-methods.index'],
            ];
        });
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/withdraw.php' => config_path('withdraw.php'),
        ], 'withdraw-config');
        $this->mergeConfigFrom(__DIR__ . '/../config/withdraw.php', 'withdraw');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'withdraw');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
    }

    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/withdraw');

        $sourcePath = __DIR__ . '/../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'withdraw-module-views']);

        $this->loadViewsFrom($sourcePath, 'withdraw');
    }
}
