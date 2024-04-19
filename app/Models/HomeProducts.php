<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeProducts extends Model
{
    protected $table = 'hba_home_products';
    protected $primaryKey = 'home_title_id';
    public $timestamps = false;
    // const CREATED_AT = 'added_datetime';
	// const UPDATED_AT = 'updated_datetime';
	
	protected $guarded = [];

	protected $dates = [
        //
    ];
}
