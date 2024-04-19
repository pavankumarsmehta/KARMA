<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPages extends Model
{
    protected $table = 'hba_static_pages';
    protected $primaryKey = 'static_pages_id';
    protected $hidden = [
        
    ];
    protected $dates = [
        
    ];

    public $timestamps = false;
}
