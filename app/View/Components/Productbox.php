<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Productbox extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
	public $prodData;
    public $advertisementData;
    public $sectionName;
    public $pageType;
	public function __construct($prodData= array(),$attr = array(),$advertisementData = array(),$pageType = '',$sectionName = '')
    {
        $this->attr = $attr;
		$this->prodData = $prodData;
        $this->sectionName = $sectionName;
        $this->pageType = $pageType;
        $this->advertisementData = $advertisementData;		
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.productbox');
    }
}
