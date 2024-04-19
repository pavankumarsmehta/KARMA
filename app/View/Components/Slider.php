<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Slider extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
	public $attr;
	public $sliderData;
    
	public function __construct($sliderData = array(),$attr = array())
    {
		$this->attr = $attr;
		$this->sliderData = $sliderData;		
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.slider');
    }
}
