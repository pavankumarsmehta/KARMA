<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $shipping_mode_id
 * @property string     $shipping_title
 * @property string     $detail
 * @property int        $display_position
 */
class ShippingRate extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hba_shipping_rate';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'shipping_rate_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
       // 'shipping_title', 'ship_carrier_type', 'detail', 'display_position', 'is_lift_gate', 'lift_gate_charge', 'status'
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
        //'shipping_mode_id' => 'int', 'shipping_title' => 'string', 'detail' => 'string', 'display_position' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        
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
