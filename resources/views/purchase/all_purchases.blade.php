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
		      		      
		    		<tr>
			      		<th class="align-middle" scope="row">uzair</th>
				      	<td class="align-middle">asdf</td>
				      	<td class="align-middle">asdf</td>
				      	<td class="align-middle">asdf</td>
				      	
				      	
				      	
				      	
				      	<td class="align-middle">asdf</td>
				      	<td class="align-middle">asdf</td>
				      	
				      	<td class="align-middle">asdf</td>
				      	<td class="align-middle"><a href="#">Update</a></td>
				      	<td class="align-middle">
			      			
				      			<a href="#">
				      		    	<button class="btn btn-link p-0" data-toggle="modal" data-target="">Delete</button>
				      			</a>
			      			<!-- </form> -->
				      	</td>
				      	<td class="align-middle"><a href="#">Download</a></td>
				      	
				      	
		    		</tr>
		<!-- Model -->
		<div class="modal fade" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <form action="#" method="POST">
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
		      	
		 	</tbody>
		</table>
	</div>
</div>
@endsection