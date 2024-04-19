<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'hba_products';
    protected $primaryKey = 'product_id';
    public $timestamps = true;
    const CREATED_AT = 'added_datetime';
    const UPDATED_AT = 'updated_datetime';

    protected $guarded = [];

    protected $dates = [
        'added_datetime', 'updated_datetime'
    ];

    //protected $appends = ['product_name'];

    public function productsCategory()
    {
        return $this->hasMany(ProductsCategory::class, 'products_id');
        //return $this->belongsTo(ProductsCategory::class, 'products_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_category', 'products_id', 'category_id');
    }

    public function getProductNameAttribute()
    {
        $str = $this->attributes['product_name'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('\\r\n', '', $str);
        $str = str_replace('/\/', '', $str);
        $str = str_replace('#', '', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
    public function getSizeDimensionAttribute()
    {
        $str = $this->attributes['size_dimension'] ?? null;
        //$str = str_ireplace(array("\r","\n",'\r','\n'),'',$str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
    public function getGeneralInformationAttribute()
    {
        $str = $this->attributes['general_information'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        //return preg_replace('/[^A-Za-z0-9\s"]+/', '', $string); // 
        return $str;
    }
    public function getSpecificationAttribute()
    {
        $str = $this->attributes['specification'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
    public function getBenchDimensionAttribute()
    {
        $str = $this->attributes['bench_dimension'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
    public function getTableDimensionAttribute()
    {
        $str = $this->attributes['table_dimension'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
    public function getChairDimensionAttribute()
    {
        $str = $this->attributes['chair_dimensions'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
    public function getDescriptionAttribute()
    {
        $str = $this->attributes['description'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
    public function getCareAttribute()
    {
        $str = $this->attributes['care'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
    public function getPileHeightAttribute()
    {
        $str = $this->attributes['pile_height'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
    public function getThicknessAttribute()
    {
        $str = $this->attributes['thickness'] ?? null;
        $str = str_ireplace(array("\r", "\n", '\r', '\n'), '', $str);
        $str = str_replace('?', 'X', $str);
        $str = stripslashes($str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = html_entity_decode($str);
        return $str;
    }
}
