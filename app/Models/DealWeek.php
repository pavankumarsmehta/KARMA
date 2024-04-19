<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealWeek extends Model
{	
	 
    public $timestamps = false;
    
    protected $table = 'hba_dealofweek'; 
    
    protected $primaryKey = 'dealofweek_id';
    
    protected $fillable = ['product_sku', 'start_date', 'end_date', 'description', 'deal_price', 'status', 'display_rank', 'display_on_home', 'deal_type'];
	
	protected $dates = ['start_date','end_date'];
	
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_sku','sku');
    }
    public function scopeWhere($query,$fieldName,$fieldValue)
    {
        return $query->where($fieldName, $fieldValue);
    }
    public function scopeDateWhere($query,$fieldName,$fieldValue,$operator)
    {
        return $query->whereDate($fieldName, $operator, $fieldValue);
    }
}

?>
