<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookTanslations extends Model
{
	protected $table = 'book_translations';  
	 
    /**
     * The attributes that are mass assignable.
     *
     * @var array                                            
     */
    protected $fillable = [
       'name','description','language_id','book_id'
    ];
	
	public static $rules = array(
	  	   
		'name.*' => 'required|unique:book_translations,name', 
		'description.*' => 'required', 
    );
	
	public function language() 
    {
        return $this->hasOne('App\Language','id','language_id');
    }
}


