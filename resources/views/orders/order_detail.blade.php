@extends('layouts.app')

@section('title', 'Products')

@section('content')
 
<div class="container table-responsive">
	<div class="row">
		<table class="table">
		  	<thead>
		    	<tr>
		    		<th scope="col">Product Name</th>
			      	<th scope="col">Product Qty</th>
			      	<th scope="col">Rate</th>
		      		<th scope="col">Total</th>
		    	</tr>
		  	</thead>
		  	<tbody>
		      	@foreach($order_details as $order_detail)
		    		<tr>
				      	<td>{{$order_detail->product_name}}</td>
				      	<td>{{$order_detail->product_qty}}</td>
				      	<td>{{$order_detail->product_sale_rate}}</td>
				      	<td>{{$order_detail->product_total}}</td>	
		    		</tr>
		    	
		      	@endforeach
		 	</tbody>
		</table>
		
		<h3>Grand Total: {{$grand_total[0]->grand_total}}</h3>
	</div>
</div>
@endsection