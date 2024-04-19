<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeBottomHtml extends Model
{
    protected $table = 'hba_home_bottom_html';
    protected $primaryKey = 'id';
    public $timestamps = false;
    // const CREATED_AT = 'added_datetime';
	// const UPDATED_AT = 'updated_datetime';
	
	protected $guarded = [];

	protected $dates = [
        //
    ];
}
