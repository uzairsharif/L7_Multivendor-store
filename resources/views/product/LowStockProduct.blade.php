@extends('layouts.app')

@section('title', 'Products')

@section('content')
@if($products):
	<div class="container">
		<div class="d-flex justify-content-end mb-4">
	        <a target="_blank" class="mt-4 btn btn-primary" href="{{ URL::to('/download_low_stock_products') }}">Download Low Stock Products in PDF Format</a>
	    </div>
	</div>
	<div class="container table-responsive">
		<div class="row">
			<table class="table">
			  	<thead>
			    	<tr>
				      	<th scope="col">Id</th>
				      	<th scope="col">Name</th>
				      	<th scope="col">Description</th>
				      	<th scope="col">Image</th>
				      	<th scope="col">Quantity</th>
				      	<th scope="col">Purchase Price</th>
				      	
				      	<th scope="col">Sale_price</th>
				      	<th scope="col">Created At</th>
			      		<th scope="col">Updated At</th>
			      		<th scope="col">Update</th>
			      		<th scope="col">Delete</th>
			    	</tr>
			  	</thead>
			  	<tbody>
			      	@foreach($products as $product)
			    		<tr>
				      		<th scope="row">{{$product->id}}</th>
					      	<td>{{$product->name}}</td>
					      	<td>{{$product->description}}</td>
					      	<td class="product_image_container align-middle">
					      		
					      		<img class="product_image" src="{{ asset($product->img_upload_url) }}" alt="" title="">

					      	</td>
					      	<td>{{$product->stock}}</td>
					      	<td>{{$product->purchased_price}}</td>
					      	<td>{{$product->Sale_price}}</td>
					      	<td>{{$product->created_at}}</td>
					      	<td>{{$product->updated_at}}</td>
					      	<td><a href="{{ route('product.edit', $product->id) }}">Update</a></td>
					      	<td class="align-middle">
				      			<a href="#">
				      		    	<button class="btn btn-link p-0" data-toggle="modal" data-target="#exampleModal{{$loop->iteration}}">Delete</button>
				      			</a>
					      	</td>
					      	<!-- <td>
					      		<a href="#">
					      			<form action="{{ url('product' , $product->id ) }}" method="POST">
					      		    	@CSRF
					      		    	@METHOD('DELETE')
					      		    	<button>Delete</button>
					      			</form>
					      		</a>
					      	</td> -->				  				      	
			    		</tr>
			<!-- Model -->
			<div class="modal fade" id="exampleModal{{$loop->iteration}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                             <span aria-hidden="true close-btn">×</span>
	                        </button>
	                    </div>
	                   <div class="modal-body">
	                        <p>Are you sure want to delete?</p>
	                    </div>
	                    <div class="modal-footer">
	                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
	                        <!-- <button type="button" class="btn btn-danger close-modal" data-dismiss="modal" aria-hidden="true">Yes, Delete</button> -->
	                        <!-- <form action="{{ url('Del_Low' , $product->id ) }}" method="POST"> -->
	                        <form action="{{ url('Del_Low' , $product->id ) }}" method="POST">
				      		    @CSRF
				      		    <!-- @METHOD('DELETE') -->
				      			<a href="#">
				      		    	<button class="btn btn-danger close-modal">Delete</button>
				      			</a>
			      			</form>
	                    </div>
	                </div>
	            </div>
	        </div>
			      	@endforeach
			 	</tbody>
			</table>
		</div>
	</div>
@else
    <div class="container">
	    <div class="alert alert-success mt-4">
	        There are no products that are low in stock.
	    </div>
    </div>
@endif

@endsection