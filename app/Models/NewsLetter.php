<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NewsLetter extends Model
{	
    public $timestamps = false;
    protected $table = 'hba_news_letter'; 
    protected $primaryKey = 'news_letter_id';
    protected $fillable = ['email', 'first_name', 'last_name', 'insert_datetime', 'status'];
	protected $dates = ['insert_datetime'];
}
?>