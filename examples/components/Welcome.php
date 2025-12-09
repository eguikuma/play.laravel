<?php

namespace Examples\Components;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('examples::layouts.app')]
class Welcome extends Component
{
    /**
     * テンプレートを描画する
     */
    public function render(): View
    {
        return view('examples::welcome');
    }
}
