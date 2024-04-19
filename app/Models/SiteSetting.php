<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{	
	 
    public $timestamps = false;
    
    protected $table = 'hba_site_settings'; 
    
    protected $primaryKey = 'site_settings_id';
    
    protected $fillable = ['title','var_name','description','setting','display_order','section','status'];
}

?>
