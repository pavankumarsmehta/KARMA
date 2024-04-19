<?php
namespace App\View\Components;
use Illuminate\View\Component;
class DealOfWeek extends Component
{
    public $dealOfWeekData;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dealOfWeekData)
    {
        
        $this->dealOfWeekData = $dealOfWeekData;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.deal_of_week');
    }
}