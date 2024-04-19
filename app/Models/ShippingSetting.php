<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
    use HasFactory;
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $table = 'hba_shipping_setting';
    public $timestamps = true;
    protected $primaryKey = 'shipping_setting_id';
}
