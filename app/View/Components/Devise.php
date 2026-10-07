<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Devise extends Component
{
    public function render(): View
    {
        return view('components.devise', ['devise' => 'AED']);
    }
}
