<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $shipping_mode_id
 * @property string     $shipping_title
 * @property string     $detail
 * @property int        $display_position
 */
class PaymentMethod extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hba_payment_methods';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'pm_id';

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
