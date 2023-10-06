@extends('layouts.app')

@section('title', 'Products')

@section('content')
 
<div class="container table-responsive">
	<div class="row">
		<table class="table">
		  	<thead>
		    	<tr>
			      	<th scope="col">Id</th>
			      	<th scope="col">Name</th>
			      	<th scope="col">Description</th>
			      	<th scope="col">Created At</th>
		      		<th scope="col">Updated At</th>
		      		<th scope="col">Update</th>
		      		<th scope="col">Delete</th>
		    	</tr>
		  	</thead>
		  	<tbody>
		      	@foreach($vendors as $vendor)
		    		<tr>
			      		<th class="align-middle" scope="row">{{$vendor->id}}</th>
				      	<td class="align-middle">{{$vendor->name}}</td>
				      	<td class="align-middle">{{$vendor->description}}</td>
				      	
				      	<td class="align-middle">{{$vendor->created_at}}</td>
				      	<td class="align-middle">{{$vendor->updated_at}}</td>
				      	<td class="align-middle"><a href="{{ route('vendor.edit', $vendor->id) }}">Update</a></td>
				      	<td class="align-middle">
			      			
				      			<a href="#">
				      		    	<button class="btn btn-link p-0" data-toggle="modal" data-target="#exampleModal{{$loop->iteration}}">Delete</button>
				      			</a>
			      			
				      	</td>
				      	
				      	
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
			                        <form action="{{ url('product' , $vendor
			                         ->id ) }}" method="POST">
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