<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Book extends Model
{
	protected $table = 'books';  
	 
    /**
     * The attributes that are mass assignable.
     *
     * @var array                                            
     */
    protected $fillable = [
       'published' 
    ];
	
	public static $rules = array(
	  	'published' => 'required',     
    );
	
	public function bookTrans() 
    {
		if(Session::Has('langSession')){
			$language = Session::get('langSession');
			return $this->hasOne('App\BookTanslations','book_id','id')->where('language_id',$language->id);
		}
        return $this->hasOne('App\BookTanslations','book_id','id');
    }
	
	
	public function allBookTrans() 
    {
		return $this->hasMany('App\BookTanslations','book_id','id');
	}
}


