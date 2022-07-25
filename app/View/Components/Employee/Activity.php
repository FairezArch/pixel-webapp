<?php

namespace App\View\Components\Employee;

use Illuminate\View\Component;

class Activity extends Component
{
    public $name;
    public $photo;
    public $time;
    public $text;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $photo, $time, $text)
    {
        $this->name = $name;
        $this->photo = $photo;
        $this->time = $time;
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employee.activity');
    }
}
