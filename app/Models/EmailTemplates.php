<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{	
	 
    public $timestamps = false;
    
    protected $table = 'hba_email_templates'; 
    
    protected $primaryKey = 'email_templates_id';
    
    protected $fillable = ['title','subject','mail_body','template_var_name','status'];
}

?>
