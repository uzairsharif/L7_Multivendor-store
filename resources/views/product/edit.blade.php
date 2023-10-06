@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
	<div class="container">
		<div class="row">
			<div class="mx-auto col-sm-6">
				<!-- <form action="/product/{{$product->id}}" method="POST"> -->
				<form action="{{ route('product.update',$product->id) }}" method="POST">
					@method('PUT')
					@csrf
				
					@if(Session::has('alert-success'))
					<div class="flash-message">	
	     				<p class="alert alert-success">{{ Session::get('alert-success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	     			</div>
	      			@endif
				
					<h1>Update Product Here</h1>
					
				  	<div class="form-group">
				    	<label for="nameInput">Name</label>
				    	<input type="text" readonly class="form-control" id="nameInput" placeholder="Name" name="product_name" value="{{old('product_name') ?? $product->name}}">
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
				  	</div>
				  	<div class="form-group">
				    	<label for="descriptionInput">Description</label>
				    	<input type="text" class="form-control" id="descriptionInput" aria-describedby="emailHelp" placeholder="Description" name="product_description" value="{{old('product_description') ?? $product->description}}">
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
				  	</div>

				  	<div class="form-group">
				    	<label for="salePriceInput">Sale Price</label>
				    	<input type="text" class="form-control" id="salePriceInput" aria-describedby="emailHelp" placeholder="Sale Price" name="product_sale_price"value="{{old('product_sale_price') ?? $product->product_sale_price}}">
				    	@if($errors->has('product_sale_price'))
		    				<div class="alert alert-danger">
		    					<ul>
		    						@foreach($errors->get('product_sale_price') as $error)
		    						<li>
		    							@php echo $error; @endphp		
		    						</li>
		    						@endforeach
		    					</ul>
		    				</div>
				    	@endif
				    </div>
				  	<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>	
	</div>
@endsection