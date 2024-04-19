<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaInfo extends Model
{
    
    protected $table = 'hba_meta_info'; 
    
    // protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['meta_title','meta_keywords','meta_description','type'];

	protected $dates = [
        // 
    ];
}

?>
