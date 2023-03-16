<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatusAlert extends Component
{
    // public string $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public string $value = "")
    {
        // $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.status-alert');
    }
}
