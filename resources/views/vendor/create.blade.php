@extends('layouts.app')

@section('title', 'Create Vendor')

@section('content')
	<div class="container">
		<div class="row">
			<div class="mx-auto col-sm-6">
				
				<form action="<?php echo route('vendor.store') ?>" method="POST"
					enctype="multipart/form-data">
				@if(Session::has('alert-success'))
					<div class="flash-message">	
	     				<p class="alert alert-success">{{ Session::get('alert-success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	     			</div>
      			@endif
      			@if(Session::has('alert-danger'))
      				<div class="flash-message">
      					<p class="alert alert-danger">
      						{{Session::get('alert-danger') }}
      						<a href="#" class="close" data-dismiss="alert"aria-label="close">&times;
      						</a>
      					</p>
      				</div>
      			@endif
				
				<h1>Add New Vendor Here</h1>	
					@CSRF
					<div class="form-group">
				    	<label for="idInput">ID</label>
				    	<input type="text" class="form-control" id="idInput" placeholder="ID" name="vendor_id" value="<?php echo old('vendor_id') ?>">
				    	@if($errors->has('product_id'))
		    				<div class="alert alert-danger">
		    					<ul>
		    						@foreach($errors->get('vendor_id') as $error)
		    						<li>
		    							@php echo $error; @endphp		
		    						</li>
		    						@endforeach
		    					</ul>
		    				</div>
				    	@endif				    	
				  	</div>
				  	<div class="form-group">
				    	<label for="nameInput">Name</label>
				    	<input type="text" class="form-control" id="nameInput" placeholder="Name" name="vendor_name" value="<?php echo old('vendor_name'); ?>">
				    	@if($errors->has('product_name'))
		    				<div class="alert alert-danger">
		    					<ul>
		    						@foreach($errors->get('vendor_name') as $error)
		    						<li>
		    							@php echo $error; @endphp		
		    						</li>
		    						@endforeach
		    					</ul>
		    				</div>
				    	@endif
				    	<!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
				  	</div>
				  	<div class="form-group">
				    	<label for="descriptionInput">Description</label>
				    	<input type="text" class="form-control" id="descriptionInput" aria-describedby="emailHelp" placeholder="Description" name="vendor_description" value="<?php echo old('vendor_description'); ?>">
				    	@if($errors->has('product_description'))
				    				<div class="alert alert-danger">
				    					<ul>
				    						@foreach($errors->get('product_description') as $error)
				    						<li>
				    							@php echo $error; @endphp		
				    						</li>
				    						@endforeach
				    					</ul>
				    				</div>
				    	@endif
				    	<!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
				  	</div>
				  	<button type="submit" class="btn btn-primary btn-block">Submit</button>
				</form>
			</div>
		</div>	
	</div>
@endsection