<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $quantity_discount_id
 * @property int        $quantity
 * @property Date       $start_date
 * @property Date       $end_date
 * @property string     $detail
 */
class QuantityDiscount extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hba_quantity_discount';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'quantity_discount_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'quantity_discount_amount', 'type', 'start_date', 'end_date', 'detail', 'status'
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
        'quantity_discount_id' => 'int', 'quantity' => 'int', 'start_date' => 'date', 'end_date' => 'date', 'detail' => 'string'
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
