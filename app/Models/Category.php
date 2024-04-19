<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'hba_category';
    protected $primaryKey = 'category_id';
    public $timestamps = true;
    const CREATED_AT = 'added_datetime';
	const UPDATED_AT = 'updated_datetime';
	
	protected $guarded = [];

	protected $dates = [
        'added_datetime', 'updated_datetime'
    ];

	public function children()
	{
		return $this->hasMany(self::class, 'parent_id')->orderBy('category_name', 'asc')->with('children');
	}

	public function childrenRecursive()
	{
		return $this->children()->with('childrenRecursive');
	}

	public function parent()
	{
	   return $this->belongsTo(self::class, 'parent_id')->orderBy('category_name', 'asc')->with('parent');
	}

	public function parentRecursive()
	{
	   return $this->parent()->with('parentRecursive');
	}

	public function featuredCarpet()
	{
		return $this->hasMany(CategoryFeaturedCarpet::class, 'category_id')->orderBy('popular_category_id', 'asc');
	}

	public function products()
    {
        return $this->belongsToMany('Product', 'products_categort', 'category_id', 'products_id');
    }
}
