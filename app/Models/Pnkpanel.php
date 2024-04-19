<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;

class Pnkpanel extends Authenticatable
{
    protected $table = 'hba_admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = true;
    const CREATED_AT = 'insert_datetime';
	const UPDATED_AT = 'update_datetime';
            
    protected $guard = 'pnkpanel';
    
    protected $fillable = [
        'email','password','status','admin_type','rights'
    ];
    
    protected $hidden = [
        'password', 'remember_token'
    ];
	protected $dates = [
        'insert_datetime', 'update_datetime'
    ];
}
