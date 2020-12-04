<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookTanslations;
use App\Language;
use Session; 
use Validator;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$query = Book::with('bookTrans')->orderBy('id','DESC');
		$code = 'en';
		if(\Request::Has('lang')){
			$code = \Request::get('lang');
			
		}
		$language = Language::where('code',$code)->first(); 
		Session::put('langSession',$language);
		
		if ($request->has('q')) {
			
			$str = trim($request->q);
			$query->whereHas('bookTrans',function($q) use($str){
				$q->where('name','like',"%$str%")
				->orWhere('description','like',"%$str%");
			});
			
		}
		
		$languages = Language::get();
		  
		$books = $query->paginate();
		Session::forget('langSession');
		return view('books/index')->with(compact('languages','books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
		$languages = Language::get();
        return view('books/create')->with(compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
		$rules = Book::$rules;
		$dRules = BookTanslations::$rules;
		  
		$validate = Validator::make($input, $rules);
		$dValidate = Validator::make($input, $dRules);
			 
		 
		if ( $validate->fails() || $dValidate->fails()) {
			 
			$validationMessages = array_merge_recursive($dValidate->messages()->toArray(), $validate->messages()->toArray());
			return \Redirect::back()->withErrors($validationMessages)->withInput($input);
		}
		
		$name = $input['name'];
		
		if(!empty( $name )){ 
			$book = Book::create($input);
			foreach($name as $key =>  $value){
				 
				if(isset($input['language_id'][$key])){
					$languageId = $input['language_id'][$key];
				} else {
					$languageId = NULL;
				}
				
				if(isset($input['description'][$key])){
					$description = $input['description'][$key];
				} else {
					$description = NULL;
				}
				if(isset($input['name'][$key])){
					$name = $input['name'][$key];
				} else {
					$name = NULL;
				}
				
				if(isset($input['published'][$key])){
					$published = $input['published'][$key];
				} else {
					$published = NULL;
				}
				 
				
				BookTanslations::create([
				
					'book_id' => $book->id,
					'name' => $value,
					'description' => $description,
					'language_id' => $languageId,
					
					 
				]);
			}
		}
		 
		Session::flash('message','Book successfully created.');
		Session::flash('alert-class', 'alert-success');
		return redirect('/books');
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TodoBook  $todoBook
     * @return \Illuminate\Http\Response
     */
    public function show(TodoBook $todoBook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TodoBook  $todoBook
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $book = Book::with('bookTrans','allBookTrans')->find($id);
		$languages = Language::get();
		if(!$book){
			return abort(404); 
		}
		 
		return view('books/edit')->with(compact('languages','book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TodoBook  $Book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
		$rules = Book::$rules;
		$dRules = BookTanslations::$rules;
 
		$dRules['name.*'] .= ",".$id; 
		unset($dRules['name.*'] );
		 
		$validate = Validator::make($input, $rules);
		$dValidate = Validator::make($input, $dRules);
			 
		if ( $validate->fails() || $dValidate->fails()) {
			$validationMessages = array_merge_recursive($dValidate->messages()->toArray(), $validate->messages()->toArray());
			 
			return \Redirect::back()->withErrors($validationMessages)->withInput($input);
		}
		
		/**
		 *
		 * Start unique array validation 
		 */
		 
		$name = $input['name'];
		$validErr = [];
		foreach($name as $lock => $validateNam){
			 
			$validChk = BookTanslations::where('book_id','!=',$id)
			 
			->where('name',$validateNam)->first();
			 
			if($validChk){
				$validErr[] = 'The name.'.$lock.' already taken.';
			}
		}
		 
		if(count($validErr) > 0){
			return \Redirect::back()->withErrors($validErr)->withInput($input);
		}
		
		/**
		 *
		 * End unique array validation 
		 */
		 
		$book = Book::with('bookTrans')->find($id);
		
		if( !$book ){
			return abort(404); 
		}
		$book->update($input);
		 
		$name = $input['name'];
		if(!empty($name)){
			 
			$description = '';
			$languageId  = '';
			foreach($name as $key => $nameVal){
				if(isset($input['description'][$key])){
					$description = $input['description'][$key];
				}
				
				if(isset($input['language_id'][$key])){
					$languageId = $input['language_id'][$key];
				}
				 
				    
				BookTanslations::updateOrCreate(['book_id'   => $id,'language_id' => $languageId ],[
					'name' => $nameVal,
					'description' => $description,
				
				]);
				 	  
			}
			 
		}	  	
		   
		Session::flash('message','Book successfully updated.');
		Session::flash('alert-class', 'alert-success');
		return redirect('/books');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $Book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$book = Book::with('bookTrans')->find($id);
		if(!$book){
			return abort(404); 
		}
		
		if($book->bookTrans){
			$book->bookTrans()->delete();
		}
		
		$book->delete(); 
		Session::flash('message','Book successfully deleted.');
		Session::flash('alert-class', 'alert-success');
		return redirect()->back();
    }
}
