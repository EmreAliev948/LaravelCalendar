<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LayoutApp extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.layout-app');
    }
}
