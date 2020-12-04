<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';  
	 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name', 'code'
    ];
	
	public static $rules = array(
	  	 
		'name' => 'required|unique:languages',
		'code' => 'required|unique:languages',
    );
	
	public function book() 
    {
        return $this->hasMany('App\Book','language_id','id');
    } 
	  
	 
}
