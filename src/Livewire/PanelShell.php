<?php

declare(strict_types=1);

namespace LearningLabs\FilamentClone\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

final class PanelShell extends Component
{
    public string $message = 'Learning Labs Admin Panel is ready.';

    public function render(): View
    {
        return view('filament-clone::livewire.panel-shell');
    }
}
