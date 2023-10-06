@extends('layouts.app')

@section('title', 'Products')

@section('content')

<div class="container table-responsive">
	<div class="row">
		<table class="table">
		  	<thead>
		    	<tr>
		    		<th scope="col">Order Id</th>
			      	<th scope="col">Buyer Name</th>
			      	<th scope="col">Grand Total</th>
			      	
			      	<th scope="col">Created At</th>
		      		<th scope="col">Updated At</th>
		      		
		      		@php //<th scope="col">Delete</th>
		      		@endphp
		    	</tr>
		  	</thead>
		  	<tbody>
		      	@foreach($orders as $order)
		   
		    		<tr>
			      		<td scope="row">
			      			<a href="{{route('order.show', $order->id) }}">{{$order->id}}</a></td>
				      	<td>{{$order->buyerName}}</td>
				      	<td>{{$order->grand_total}}</td>

				      	<td>{{$order->created_at}}</td>
				      	<td>{{$order->updated_at}}</td>
				      	
				      	<!-- <td><a href="{{ route('order.edit', $order->id) }}">Update</a></td> -->
				      	@php //<td>
			      			//<a href="#">
			      		    	//<button class="btn btn-link p-0"data-toggle="modal" data-target="#exampleModal{{$loop->iteration}}">Delete</button>
			      			//</a>	
				      	//</td>
				      	@endphp	
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
                        <form action="{{ url('order' , $order->id ) }}" method="POST">
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
			{{ $orders->links() }}
				@php
				$arrayy = ['foo', 'bar', 'baz', 'uzair'];
				$itm = array();
				$i = 0;
				foreach($orders as $order){
					$itm[$i] = $order->created_at;
					$i++;
				}
				
				@endphp
		<script>
			var itm= @json($arrayy);
		</script>


		<!-- <div
		    x-data="{
		        search: '',
		 
		        items: itm,
		        
		 
		        get filteredItems() {
		            return this.items.filter(
		                i => i.startsWith(this.search)
		            )
		        }
		    }"
		>
		    <input x-model="search" placeholder="Search...">
		 
		    <ul>
		        <template x-for="item in filteredItems" :key="item">
		            <li x-text="item"></li>
		        </template>
		    </ul>
		</div> -->
	</div>
</div>

@endsection