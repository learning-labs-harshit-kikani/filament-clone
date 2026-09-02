<?php

declare(strict_types=1);

namespace LearningLabs\FilamentClone\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class PanelShell extends Component
{
    public function render(): View
    {
        return view('filament-clone::components.panel-shell');
    }
}
