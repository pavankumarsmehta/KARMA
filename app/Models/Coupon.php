<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $coupon_id
 * @property string     $coupon_title
 * @property string     $coupon_number
 * @property Date       $start_date
 * @property Date       $end_date
 * @property string     $sku
 * @property string     $detail
 * @property string     $remark
 */
class Coupon extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hba_coupon';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'coupon_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon_title', 'coupon_number', 'start_date', 'end_date', 'type', 'order_amount', 'sku', 'orders', 'discount', 'detail', 'is_once', 'is_used', 'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'coupon_id' => 'int', 'coupon_title' => 'string', 'coupon_number' => 'string', 'start_date' => 'date', 'end_date' => 'date', 'sku' => 'string', 'detail' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date', 'end_date'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
