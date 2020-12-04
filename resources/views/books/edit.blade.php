@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
	 
		@if(Session::has('message'))
			<div class="alert {{ Session::get('alert-class') }}">
				<a href="#" class="close" data-dismiss="alert">&times;</a> 
				{{ Session::get('message') }}
			</div>
		 @endif	
			 
		 
		<div class="col-md-12">
			@if (isset($errors) && count($errors) > 0)
				<div class="alert alert-danger margin-top-2 clearfix">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>whoops! </strong>There were some problems with your input. <br><br>
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				
				</div>
			@endif		
            <div class="card">
				<div class="card-header">
					Edit Event  
				</div>
				<div class="card-body">
					<div class="form-group">
						<nav>
							<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
								@foreach($languages as $language)
									<a class="nav-item nav-link  @if($language->code == 'en') active show @endif" id="nav-{{$language->id}}-tab" data-toggle="tab" onCLick="selTab({{$language->id}})" href="#nav-{{$language->id}}" role="tab" aria-controls="nav-{{$language->id}}" aria-selected="true">{{$language->name}}</a>
								@endforeach
								  
							</div>
						</nav>
					</div>
					<form action="{{ url('books') }}/{{ $book->id }}" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="PUT">
						<div class="form-group">
							@if($book->allBookTrans->count() > 0 && !empty($languages))

								 
								@foreach($book->allBookTrans as $bookDescrpt)
									 
									<div class="tab-content" id="nav-tabContent">
										<div class="tab-pane old-form_{{$bookDescrpt->language->id}} @if($bookDescrpt->language->code == 'en') active show @endif" id="nav-{{$bookDescrpt->language_id}}" role="tabpanel" aria-labelledby="nav-{{$bookDescrpt->language_id}}-tab">
											  
											<div class="col-md-8">
												<div class="form-group">
													<label>Name <span class="required">*</span></label>							 					
													<input type="text" id="name" placeholder="Enter name here" name="name[{{$bookDescrpt->language_id}}]" @if(!empty(old('name')[$bookDescrpt->language_id])) value="{{ old('name.'.$bookDescrpt->language_id) }}" @else value="{{ old('name[]',$bookDescrpt->name) }}" @endif class="form-control">
												</div>
										 
												<div class="form-group">
													<label>Description <span class="required">*</span></label>
													<textarea type="text" id="description" placeholder="Enter description here" name="description[{{$bookDescrpt->language_id}}]" cols="5" rows="6" @if(!empty(old('description')[$bookDescrpt->language_id])) value="{{ old('description.'.$bookDescrpt->language_id) }}" @else value="{{ old('description[]',$bookDescrpt->description) }}" @endif class="form-control">@if(!empty(old('description')[$bookDescrpt->language_id])) {{ old('description.'.$bookDescrpt->language_id) }}  @else {{$bookDescrpt->description}} @endif</textarea>
												</div>
												 
												<input type="hidden" value="{{$bookDescrpt->language_id}}" name="language_id[{{$bookDescrpt->language_id}}]"> 
												
											</div>
											
										</div>
									</div>
									@endforeach
								@endif
						</div>
						<div class="form-group">
							<div class="col-md-8">
								<div class="form-group">
									<label>Published <span class="required">*</span></label>
									<input type="date" id="published" placeholder="Enter published date here" name="published" value="{{ old('published',$book->published) }}" class="form-control datepicker">
								</div>
								 
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-8">
							 
								<button type="submit" class="btn btn-success btn-sm">
								<i class="fa fa-paper-plane" aria-hidden="true"></i> Submit</button>
						  
								<a href="{{ url('books') }}" class="btn btn-primary btn-sm"><i class="fa fa-undo" aria-hidden="true"></i> Back</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
 
 

function selTab(id){
	$('.tab-pane').hide()
	$('#nav-'+id).show()
	 
}
</script>
@endsection
