<?php

namespace App\View\Components\Employee;

use Illuminate\View\Component;

class DoublePanel extends Component
{
    public $names;
    public $icons;
    public $texts;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($names, $icons, $texts)
    {
        $this->names = $names;
        $this->icons = $icons;
        $this->texts = $texts;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employee.double-panel');
    }
}
