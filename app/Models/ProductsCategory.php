<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsCategory extends Model
{
    protected $table = 'hba_products_category';
    protected $primaryKey = 'products_category_id';
    public $timestamps = true;
    const CREATED_AT = 'added_datetime';
	const UPDATED_AT = 'updated_datetime';
	
	protected $guarded = [];

	protected $dates = [
        'added_datetime', 'updated_datetime'
    ];
}
