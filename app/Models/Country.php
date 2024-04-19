<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'hba_countries';

    public $timestamps = false;
             
    protected $primaryKey = 'countries_id';
                
	protected $dates = [
        // 'updated_at'
    ];
}
