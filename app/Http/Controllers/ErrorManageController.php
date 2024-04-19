<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\generalTrait;

class ErrorManageController extends Controller
{
    use generalTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $this->PageData['CSSFILES'] = ['slick.css'];
		$this->PageData['JSFILES'] = ['slick.js','category.js'];
        return view("errors.404")->with($this->PageData);
    }
}
