  
@extends('layouts.app')
@section('content')

<div class="container">
	<div class="col-md-12 mb-4">
		@if(Session::has('message'))
			<div class="alert {{ Session::get('alert-class') }}">
				<a href="#" class="close" data-dismiss="alert">&times;</a> 
				{{ Session::get('message') }}
			</div>
		 @endif	
		
	</div>
	<div class="dropdown mb-3">
		<button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="choose-language-text">Choose Language</span>
		</button>

		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			@if(!empty($languages))
			@foreach($languages as  $language)
									
				@if($language->code == 'en' && Request::get('lang') == '')
				<a href="books/?lang={{$language->code}}" data-local-lan="{{ $language->name }}" class="dropdown-item lan-dropdown  active" href="#">
					 {{ ucwords($language->name) }} 
				</a>
				@else
				<a href="books/?lang={{$language->code}}" data-local-lan="{{ $language->name }}" class="dropdown-item lan-dropdown  @if(Request::get('lang') ==  $language->code) active @endif" href="#">
					 {{ ucwords($language->name) }} 
				</a>
				@endif
				  
			@endforeach
			@endif
		</div>
	</div>
	<div class="card">
		<div class="card-header">
			  Books Listing
		</div>
		<div class="card-body">
			  
			<div class="row">
				<div class="col-md-12">
					  	
						<div class="table-responsive">
							
							<div class="col-md-12 mb-4 mt-4">
								<div class="row form-inline">
									<div class="col-md-8">
									@if(\Request::Has('lang')) @php $sCode =  \Request::get('lang') @endphp @else @php $sCode = '' @endphp @endif
										 <form action="{{ url('books') }}" method="GET" class="form-inline">
											{{ csrf_field() }}
											<input type="hidden" value="{{$sCode}}" name="lang">
											<input type="text" value="{{Request::input('q')}}"  name="q" placeholder="Search....." class="form-control search">
											<button type="submit" class="btn btn-primary btn-sm ml-2">
												<i class="fa fa-search"></i>
											</button>
										</form> 
									</div>
									 <div class="col-md-4">
										<a href="{{ url('/books/create') }}" class="btn btn-sm btn-success  float-right">
											<i class="fa fa-plus"> Add Book </i>
										</a>
									</div>
								</div> 
							</div> 
								
							<table class="table table-bordered">
								<thead>
									<tr>
										 
										<th>#</th>
										<th>Name</th>
										<th>Description</th>
										<th>Published</th>
										<th>Action</th>
									</tr>
									
								</thead>
								
								<tbody>
								 
								<?php 
									$i = (Request::input('page')) ?  (Request::input('page') -1) * $events->perPage() + 1 : 1; 
								?>
								@if(isset($books) && count($books) > 0)
								@foreach($books as $value)
								  
									<tr>
									
										<td>{{ __($i++) }} </td>
										<td>{{ $value->bookTrans['name']   }}</td>
										<td>{{ $value->bookTrans['description']   }}</td>
										<td>{{ __( ucwords( $value->published  ) ) }}</td>
									  
										<td style="width: 500px;">
											<form method="POST" action="{{ URL('/books/')}}/{{$value->id}}" id="delete_{{ $value->id }}" accept-charset="UTF-8" class="form-inline">
												{{ csrf_field() }}
												<input type="hidden" name="_method" value="DELETE">	
											 
												<a class="btn btn-sm btn-primary ml-2" href="{{ url('books') }}/{{ $value->id }}/edit"><i class="fa fa-edit"> </i> Edit</a>
												  
												<a href="#" class="btn btn-danger btn-sm ml-2" data-toggle="modal" data-target="#dltModal" onClick="deleteBtn({{ $value->id }})" aria-hidden="true"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
												  
												  
											</form>
										</td> 
									 
									</tr>
								 
								 
								@endforeach
								<tr>
									<td colspan="7">
										{{  $books->appends(Request::only('q'))->links() }}
									 </td>
								</tr>
								@else
									<tr><td colspan="7">No books found</td></tr>
								@endif
								
								</tbody>
							</table>
							
						</div>
					   
				</div>
			</div>
			 
		</div>
	</div>
	<!-- Delete Modal -->
	<div class="modal fade" id="dltModal" tabindex="-1" role="dialog" aria-labelledby="dltModal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Delete Book</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			Do you want to delete the Book?
		  </div>
		  <div class="modal-footer">
			<input type="hidden" id="pId">
			<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
			<button type="submit" id="yes-btn" class="btn btn-danger btn-sm">Yes</button>
		  </div>
		</div>
		 
	  </div>
	</div>
	<!-- End Delete Modal -->
	 
</div>
 <script>

	
	function deleteBtn(id){
		
		$('#pId').val(id);
	};
	$('body').on('click', '#yes-btn', function() {
		var id = $('#pId').val();
		$('#delete_' + id).submit();
	});
	 
</script>
@endsection
