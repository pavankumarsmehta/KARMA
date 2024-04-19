<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $shipping_mode_id
 * @property string     $shipping_title
 * @property string     $detail
 * @property int        $display_position
 */
class ShippingRule extends Model
{
    protected $table = 'hba_shipping_rule';
    protected $primaryKey = 'shipping_rule_id';
    public $timestamps = false;

   

}
