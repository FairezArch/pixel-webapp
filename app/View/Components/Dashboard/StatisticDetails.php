<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class StatisticDetails extends Component
{
    public $name;
    public $listType;
    public $list;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $listType, $list)
    {
        $this->name = $name;
        $this->listType = $listType;
        $this->list = $list;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard.statistic-details');
    }
}
