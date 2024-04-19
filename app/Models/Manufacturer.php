<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $table = 'hba_manufacturer';
    protected $primaryKey = 'manufacturer_id';
    public $timestamps = false;
    const CREATED_AT = 'added_datetime';
	const UPDATED_AT = 'updated_datetime';
	
	protected $guarded = [];
	 
	protected $dates = [
        'added_datetime', 'updated_datetime'
    ];
}
