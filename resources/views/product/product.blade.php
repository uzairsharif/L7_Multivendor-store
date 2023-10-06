@extends('layouts.app')

@section('title', 'Products')

@section('content')
 
<div class="container table-responsive">
	<div class="d-flex justify-content-end mb-4">
        <a class="mt-4 btn btn-primary" target="_blank" href="{{ URL::to('/download_all_products') }}">Download All Products in PDF Format</a>
    </div>
	<div class="row">
		<table class="table">
		  	<thead>
		    	<tr>
			      	<th scope="col">Id</th>
			      	<th scope="col">Name</th>
			      	<th scope="col">Description</th>
			      	<th scope="col">Quantity</th>
			      	<th scope="col">Image</th>
			      	<th scope="col">Purchase Price</th>
			      	<th scope="col">Sale_price</th>
			      	<th scope="col">Created At</th>
		      		<th scope="col">Updated At</th>
		      		<th scope="col">Update</th>
		      		<th scope="col">Delete</th>
		      		<th scope="col">Download</th>
		    	</tr>
		  	</thead>
		  	<tbody>
		      	@foreach($products as $product)		      
		    		<tr>
			      		<th class="align-middle" scope="row">{{$product->id}}</th>
				      	<td class="align-middle">{{$product->name}}</td>
				      	<td class="align-middle">{{$product->description}}</td>
				      	<td class="align-middle">{{$product->stock}}</td>
				      	<!-- <td><a href="{{$product->img_upload_url}}">View Image</a></td> -->
				      	
				      	<td class="product_image_container align-middle">
				      		
				      		<img class="product_image" src="{{ asset($product->img_upload_url) }}" alt="" title="">

				      	</td>
				      	<!-- <td><img src="{{ asset('/storage/lota2.jpg') }}" alt="" title=""></td> -->
				      	<!-- <td><img src="/storage/lota.jpg" alt=""></td> -->
				      	<!-- <td><img src="{{$product->img_upload_url}}" alt=""></td> -->
				      	<td class="align-middle">{{$product->purchased_price}}</td>
				      	<td class="align-middle">{{$product->Sale_price}}</td>
				      	@php
				      	//<td class="align-middle">//{{$product->created_at->timezone('Asia/Karachi')->format('d/m/Y H:i:s')}}</td>
				      	
				      	@endphp
				      	<td class="align-middle">{{$product->created_at}}</td>
				      	@php
				      	//<td class="align-middle">//{{$product->updated_at->timezone('Asia/Karachi')->format('d/m/Y H:i:s')}}</td>
				      	@endphp
				      	<td class="align-middle">{{$product->updated_at}}</td>
				      	<td class="align-middle"><a href="{{ route('product.edit', $product->id) }}">Update</a></td>
				      	<td class="align-middle">
			      			<!-- <form action="{{ url('product' , $product->id ) }}" method="POST"> -->
				      		    <!-- @CSRF -->
				      		    <!-- @METHOD('DELETE') -->
				      			<a href="#">
				      		    	<button class="btn btn-link p-0" data-toggle="modal" data-target="#exampleModal{{$loop->iteration}}">Delete</button>
				      			</a>
			      			<!-- </form> -->
				      	</td>
				      	<td class="align-middle"><a href="{{ route('product.download', $product->id) }}">Download</a></td>
				      	<!-- <td><a href="{{ route('product.destroy', $product->id) }}">Delete</a></td> -->
				      	
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
                        <form action="{{ url('product' , $product->id ) }}" method="POST">
			      		    @CSRF
			      		    @METHOD('DELETE')
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
@endsection