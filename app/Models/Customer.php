<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'hba_customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = true;
    const CREATED_AT = 'reg_datetime';
    const UPDATED_AT = 'upd_datetime';
            
    protected $guard = 'admin';
    
    protected $fillable = [
        'first_name', 'last_name', 'user_name', 'password', 'reset_token', 'email', 'company_name', 'address1', 'address2', 'phone', 'fax', 'city', 'state', 'zip', 'country', 'registration_type', 'reg_datetime', 'upd_datetime', 'status', 'customer_ip', 'customer_browser'
    ];
    
    protected $hidden = [
        // 'password', 'remember_token'
    ];
    protected $dates = [
        'reg_datetime', 'upd_datetime'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}