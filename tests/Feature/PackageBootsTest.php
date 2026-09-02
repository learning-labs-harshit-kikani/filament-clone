<?php

declare(strict_types=1);

namespace LearningLabs\FilamentClone\Tests\Feature;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LearningLabs\FilamentClone\FilamentCloneServiceProvider;
use LearningLabs\FilamentClone\Livewire\PanelShell as LivewirePanelShell;
use LearningLabs\FilamentClone\Tests\TestCase;
use Livewire\Livewire;

final class PackageBootsTest extends TestCase
{
    public function test_the_package_service_provider_boots_in_a_laravel_application(): void
    {
        $this->assertInstanceOf(Application::class, $this->app);
        $this->assertTrue($this->app->providerIsLoaded(FilamentCloneServiceProvider::class));
    }

    public function test_the_provider_loads_package_configuration_views_and_translations(): void
    {
        $this->assertSame([], config('filament-clone'));
        $this->assertSame('Learning Labs Admin Panel is ready.', __('filament-clone::messages.package_ready'));
        $this->assertTrue(view()->exists('filament-clone::components.panel-shell'));
    }

    public function test_the_provider_registers_publishable_resources_and_components(): void
    {
        $publishedConfig = ServiceProvider::pathsToPublish(FilamentCloneServiceProvider::class, 'filament-clone-config');

        $this->assertCount(1, $publishedConfig);
        $this->assertSame(
            LivewirePanelShell::class,
            app('livewire.finder')->resolveClassComponentClassName('learning-labs-filament-clone.panel-shell'),
        );
        $this->blade('<x-filament-clone::panel-shell>Blade component content</x-filament-clone::panel-shell>')
            ->assertSee('Blade component content');
    }

    public function test_the_registered_livewire_component_renders(): void
    {
        Livewire::test('learning-labs-filament-clone.panel-shell')
            ->assertSee('Learning Labs Admin Panel is ready.');
    }
}
