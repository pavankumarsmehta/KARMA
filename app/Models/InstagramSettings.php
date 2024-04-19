<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramSettings extends Model
{
    protected $table = 'hba_instagram_settings';
    protected $primaryKey = 'instagram_settings_id';
    protected $hidden = [
        
    ];
    protected $dates = [
        
    ];

    public $timestamps = false;
}
