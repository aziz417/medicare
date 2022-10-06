<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Loader extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return <<<'blade'
<div class="app-loader main-loader">
    <div class="loader-box">
        <div class="bounceball"></div>
        <div class="text">Medics<span>BD</span></div>
    </div>
</div>
blade;
    }
}
