<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportTaxRules extends Model
{
    use HasFactory;
    const UPDATED_AT = NULL;
    const CREATED_AT = NULL;
    protected $table = 'hba_import_tax_rules';
    public $timestamps = true;
    protected $primaryKey = 'import_tax_id';  
    public $fillable = ["Country", "State", "ZipCode", "TaxRegionName", "StateRate", "EstimatedCombinedRate", "EstimatedCountyRate", "EstimatedCityRate", "EstimatedSpecialRate", "RiskLevel"];

}
