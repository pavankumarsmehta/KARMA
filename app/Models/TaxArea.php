<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxArea extends Model
{
    use HasFactory;
    const UPDATED_AT = NULL;
    const CREATED_AT = NULL;
    protected $table = 'hba_tax_areas';
    public $timestamps = true;
    protected $primaryKey = 'tax_areas_id';    

}
