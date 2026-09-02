<?php

declare(strict_types=1);

namespace LearningLabs\FilamentClone;

use Illuminate\Support\ServiceProvider;
use LearningLabs\FilamentClone\Livewire\PanelShell as LivewirePanelShell;
use LearningLabs\FilamentClone\View\Components\PanelShell as BladePanelShell;
use Livewire\Livewire;

final class FilamentCloneServiceProvider extends ServiceProvider
{
    private const PACKAGE_NAME = 'filament-clone';

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/filament-clone.php', self::PACKAGE_NAME);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', self::PACKAGE_NAME);
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', self::PACKAGE_NAME);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewComponentsAs(self::PACKAGE_NAME, [BladePanelShell::class]);

        Livewire::component('learning-labs-filament-clone.panel-shell', LivewirePanelShell::class);

        $this->publishes([
            __DIR__.'/../config/filament-clone.php' => config_path('filament-clone.php'),
        ], 'filament-clone-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filament-clone'),
        ], 'filament-clone-views');

        $this->publishes([
            __DIR__.'/../resources/lang' => lang_path('vendor/filament-clone'),
        ], 'filament-clone-translations');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-clone-migrations');

        $this->publishes([
            __DIR__.'/../resources/css' => public_path('vendor/filament-clone/css'),
            __DIR__.'/../resources/js' => public_path('vendor/filament-clone/js'),
        ], 'filament-clone-assets');
    }
}
