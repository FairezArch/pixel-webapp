<?php

namespace App\View\Components\Others;

use Illuminate\View\Component;

class ResetConfirmationModal extends Component
{
    public $name;
    public $url;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.others.reset-confirmation-modal');
    }
}
