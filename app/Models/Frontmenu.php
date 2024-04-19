<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frontmenu extends Model
{
    protected $table = 'hba_menu_front';
    protected $primaryKey = 'menu_id';
    public $timestamps = false;
    const CREATED_AT = 'added_datetime';
	const UPDATED_AT = 'updated_datetime';
	
	protected $guarded = [];
	 
	protected $dates = [
        'added_datetime', 'updated_datetime'
    ];

   
    public function children()
	{
		return $this->hasMany(self::class, 'parent_id')->orderBy('menu_title', 'asc')->with('children');
	}
    public function childrenRecursive()
	{
		return $this->children()->with('childrenRecursive');
	}

	
}
