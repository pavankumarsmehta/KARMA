<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $shipping_mode_id
 * @property string     $shipping_title
 * @property string     $detail
 * @property int        $display_position
 */
class ShippingCharge extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hba_shipping_charge';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'shipping_rule_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipping_rule_id', 'shipping_mode_id', 'country', 'state', 'additonal_charge'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
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
