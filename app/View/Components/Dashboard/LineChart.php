<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class LineChart extends Component
{
    public $chartTitle;
    public $name;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($chartTitle, $name)
    {
        $this->chartTitle = $chartTitle;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard.line-chart');
    }
}
