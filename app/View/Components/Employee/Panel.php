<?php

namespace App\View\Components\Employee;

use Illuminate\View\Component;

class Panel extends Component
{
    public $name;
    public $icon;
    public $text;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $icon, $text)
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employee.panel');
    }
}
