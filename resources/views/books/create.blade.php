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
					Add Book  
				</div>

				<div class="card-body">
					<div class="form-group">
						<nav>
							<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
								@if(!empty($languages))
									@foreach($languages as $language)
										<a class="nav-item nav-link  @if($language->code == 'en') active show @endif"id="nav-{{$language->id}}-tab" data-toggle="tab" onCLick="selTab({{$language->id}})" href="#nav-{{$language->id}}" role="tab" aria-controls="nav-{{$language->id}}" aria-selected="true">{{$language->name}}</a>
									@endforeach
								@endif
								  
							</div>
						</nav>
					</div>
					<form action="{{ url('books') }}" autocomplete="off" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
						<div class="form-group">
							@if(!empty($languages) && $languages->count() > 0)
								@foreach($languages as $language)
								<div class="tab-content" id="nav-tabContent">
									<div class="tab-pane @if($language->code == 'en') active show @endif" id="nav-{{$language->id}}" role="tabpanel" aria-labelledby="nav-{{$language->id}}-tab">
										
										<div class="col-md-8">
											<div class="form-group">
												<label>Name <span class="required">*</span></label>
												<input type="text" placeholder="Enter Name here" id="title_{{$language->id}}"  name="name[{{$language->id}}]" value="{{ old('name.'.$language->id) }}" class="form-control">
											</div>
											<div class="form-group">
												<label>Description <span class="required">*</span></label>
												<textarea type="text" placeholder="Enter description here" id="description_{{$language->id}}"  name="description[{{$language->id}}]" cols="5" rows="6" value="{{ old('description.'.$language->id) }}" class="form-control">{{ old('description.'.$language->id) }}</textarea>
											</div>
											  
											<input type="hidden" value="{{$language->id}}" name="language_id[{{$language->id}}]">
											
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
									<input type="date" placeholder="Enter published here"  id="published"  name="published" value="{{ old('published') }}" class="form-control datepicker">
								</div>
								 
							</div>
						</div>			
						<div class="form-group">
							<div class="col-md-8">
								<button type="submit" id="submit" class="btn btn-success btn-sm">
								<i class="fa fa-paper-plane" aria-hidden="true"></i> Submit</button>
											
								<a href="{{ url('books') }}" id="back" class="btn btn-primary btn-sm"><i class="fa fa-undo" aria-hidden="true"></i> Back</a>
							</div>
						</div>
					</form>
				</div>
			</div>
        </div>
	</div>
</div>
 
<script>
$('.datepicker').datetimepicker({
  format:"Y-m-d H:i:s",
  validateOnBlur: false,
  step:15
});
function selTab(id){
	$('.tab-pane').hide()
	$('#nav-'+id).show()
}
</script>
@endsection
