<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $table = 'hba_quotations';
    protected $primaryKey = 'quotation_id';
    public $timestamps = true;
    const CREATED_AT = 'last_updated';
	const UPDATED_AT = 'last_updated';
	
	protected $guarded = [];

	protected $dates = [
        'last_updated', 'updated_datetime'
    ];
}
