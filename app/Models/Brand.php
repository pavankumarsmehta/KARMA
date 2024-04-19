<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'hba_brand';
    protected $primaryKey = 'brand_id';
    public $timestamps = false;
    const CREATED_AT = 'added_datetime';
	const UPDATED_AT = 'updated_datetime';
	
	protected $guarded = [];
	 
	protected $dates = [
        'added_datetime', 'updated_datetime'
    ];
    function product(){
        return $this->hasMany(Product::class, 'brand_id','brand_id');
   }
}
