@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
	<div class="container">
		<div class="row">
			<div class="mx-auto col-sm-6">
				<!-- <form method="post" action="/product"> -->
				<form action="<?php echo route('product.store') ?>" method="POST"
					enctype="multipart/form-data">
				<!-- <form action="<?php //echo route('product.create') ?>" method="GET"> -->
				<!-- ye ooper wala code flash wala mst check krnay k liay kam krta hai. matlab ka controller ma flash jo hai wo msg idhar bhejta hai apr get method use krna hoga jaisay k ooper kia... -->
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
				
				<h1>Add New Product Here</h1>
					<!-- @method('post') -->
					@CSRF
					<!-- @if ($errors->any())
					    <div class="alert alert-danger">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
					@endif -->
					<div class="form-group">
				    	<label for="idInput">ID</label>
				    	<input type="text" class="form-control" id="idInput" placeholder="ID" name="product_id" value="<?php echo $new_product_id; ?>">
				    	@if($errors->has('product_id'))
		    				<div class="alert alert-danger">
		    					<ul>
		    						@foreach($errors->get('product_id') as $error)
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
				    	<label for="nameInput">Name</label>
				    	<input type="text" class="form-control" id="nameInput" placeholder="Name" name="product_name" value="<?php echo old('product_name'); ?>">
				    	@if($errors->has('product_name'))
		    				<div class="alert alert-danger">
		    					<ul>
		    						@foreach($errors->get('product_name') as $error)
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
				  		 
				  	    <label for="Product_Categor">Product Category</label>
				  	    <select name="product_category" class="form-control" id="Product_Categor">
				  	     @foreach($product_categories as $product_category) 
				  	     	
				  	     	<option value="{{$product_category->id}}">{{$product_category->category_name}}</option>
				  	     @endforeach 	 
				  	    </select>
				    </div>
				  	<div class="form-group">
				    	<label for="descriptionInput">Description</label>
				    	<input type="text" class="form-control" id="descriptionInput" aria-describedby="emailHelp" placeholder="Description" name="product_description" value="<?php echo old('product_description'); ?>">
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
				  		<div class="form-group">
				  	  	<label for="quantityInput">Quantity</label>
				  	  	<input type="text" class="form-control" id="quantityInput"placeholder="Quantity" name="product_quantity" value="<?php echo old('product_quantity'); ?>">
				  	  	@if($errors->has('product_quantity'))
				  	  				<div class="alert alert-danger">
				  	  					<ul>
				  	  						@foreach($errors->get('product_quantity') as $error)
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
				    	<label for="purPriceInput">Purchase Price</label>
				    	<input type="text" class="form-control" id="purPriceInput" aria-describedby="emailHelp" placeholder="Purchase Price" name="product_purchase_price" value="<?php echo old('product_purchase_price'); ?>">
				    	@if($errors->has('product_purchase_price'))
				    				<div class="alert alert-danger">
				    					<ul>
				    						@foreach($errors->get('product_purchase_price') as $error)
				    						<li>
				    							@php echo $error; @endphp		
				    						</li>
				    						@endforeach
				    					</ul>
				    				</div>
				    	@endif
				    	<!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
				  	</div>
				<!-- image code is here -->
				  	<div class="form-group">
				    	<label for="productImage">Product Image</label>
				    	<input type="file" class="form-control file" id="productImage" name="product_image">
				    	@if($errors->has('productImage'))
		    				<div class="alert alert-danger">
		    					<ul>
		    						@foreach($errors->get('product_purchase_price') as $error)
		    						<li>
		    							@php echo $error; @endphp		
		    						</li>
		    						@endforeach
		    					</ul>
		    				</div>
				    	@endif
				  	</div>
				  	<!-- <label for="">Select an iamge: <INPUT TYPE="file" name="photo" id=""></label> -->
				  	<!-- <div class="form-group">
					  	<div class="custom-file">
						    <input type="file" class="custom-file-input" id="productImage" required>
						    <label class="custom-file-label" for="productImage">Choose Product Image</label>
						</div>
					</div> -->
				<!-- image code ends here. -->
				  	<button type="submit" class="btn btn-primary btn-block">Submit</button>
				</form>
			</div>
		</div>	
	</div>
@endsection