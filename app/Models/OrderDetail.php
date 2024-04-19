<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'hba_order_detail';
    protected $primaryKey = 'order_detail_id';
    public $timestamps = false;
	
	protected $guarded = [];

	public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    public function product() {
        return $this->belongsTo(Product::class, 'products_id');
    }
    
}
