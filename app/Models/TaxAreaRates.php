<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAreaRates extends Model
{
    use HasFactory;
    const UPDATED_AT = NULL;
    const CREATED_AT = NULL;
    protected $table = 'hba_tax_rates';
    public $timestamps = true;
    protected $primaryKey = 'tax_rates_id';    

}
