<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'hba_currencies';
    protected $primaryKey = 'currencies_id';
    public $timestamps = true;
    const CREATED_AT = 'last_updated';
	const UPDATED_AT = 'last_updated';
	
	protected $guarded = [];

	protected $dates = [
        'last_updated', 'updated_datetime'
    ];
}
