<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeCurrency extends Model
{	
	 
    public $timestamps = false;
    
    protected $table = 'hba_exchangecurrency'; 
    
    protected $primaryKey = 'currency_id';
    
    protected $fillable = ['currency_code','currency_name','exchange_rate','currency_symbol','status','update_datetime','currency_symbol_mobile'];
}

?>
