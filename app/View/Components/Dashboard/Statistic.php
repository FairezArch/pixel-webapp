<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class Statistic extends Component
{
    public $statisticTitle;
    public $name;
    public $listType;
    public $data;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($statisticTitle, $name, $listType, $data)
    {
        $this->statisticTitle = $statisticTitle;
        $this->name = $name;
        $this->listType = $listType;
        $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard.statistic');
    }
}
