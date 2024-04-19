<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportProducts extends Model
{
    use HasFactory;
    const UPDATED_AT = NULL;
    const CREATED_AT = NULL;
    protected $table = 'hba_export_products';
    public $timestamps = true;
    protected $primaryKey = 'import_product_id';  
    protected $hidden = ['import_product_id'];
}
