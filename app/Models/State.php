<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $table = 'hba_state';

    public $timestamps = false;
             
    protected $primaryKey = 'state_id';
                
	protected $dates = [
        // 'updated_at'
    ];
}
