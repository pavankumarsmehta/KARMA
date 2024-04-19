<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeImage extends Model
{
    protected $table = 'hba_home_image';
    protected $primaryKey = 'image_id';
    public $timestamps = false;
    // const CREATED_AT = 'added_datetime';
	// const UPDATED_AT = 'updated_datetime';
	
	protected $guarded = [];

	protected $dates = [
        //
    ];
}
