<?php

declare(strict_types=1);

namespace LearningLabs\FilamentClone\Tests;

use LearningLabs\FilamentClone\FilamentCloneServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:MTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkwMTI=');
    }

    /**
     * @return array<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FilamentCloneServiceProvider::class,
        ];
    }
}
