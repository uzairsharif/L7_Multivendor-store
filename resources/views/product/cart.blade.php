@extends('layouts.guest')
@section('title', 'Cart')
@section('content')
	<div style="margin-top:10px;" class="container">
		<div class="row">
			@if(session()->has('message'))
				<div class="col-md-12">
					<p class="alert alert-danger">
						{{ session()->get('message') }}
					</p>
				</div>
			@endif
		</div>
		<div class="row">
			<div class="col-sm-12">
				<h2>Shopping Cart Page</h2>
			</div>
		@if(isset($cart) && $cart->getContents()) 
		   <!-- agar ye issue kray to issay use na krain aur jb tutorial ko follow krtay huay need ho issay use krain. -->
			<div class="cart table-responsive">
				<table class="table table-hover shopping-cart-wrap">
					<thead class="text-muted">
						<tr>
							<th scope="col">Product</th>
							<th scope="col" width="120">Quantity</th>
							<th scope="col" width="120">Price</th>
							<th scope="col" width="200" class="text-right">Action</th>
						</tr>
					</thead>
					<tbody>
					@foreach($cart->getContents() as $id => $product_with_ids_array) 

						@foreach($product_with_ids_array as $key => 				$single_users_product_data)
						
						<tr>
							<td>
								<figure class="media">
									<div class="img-wrap">
										<img width="75px" src="{{asset($single_users_product_data['product']->img_upload_url)}}"
										class="img-thumbnail img-sm">
									</div>
									<figcaption class="media-body">
										<!-- <h6 class="title text-truncate">{{$single_users_product_data['product']->name}}</h6> -->
										<dl class="param param-inline small">
											<dt>Name: {{$single_users_product_data['product']->name}} </dt>
											<dt>Description: {{$single_users_product_data['product']->description}} </dt>
											<dt>Stock: {{$single_users_product_data['product']->stock}} </dt>
											<dt>Rate: {{$single_users_product_data['product']->Sale_price}} </dt>
											
										</dl>
										<!-- <dl class="param param-inline small">
											<dt>Color: </dt>
											<dd>Name: {{$single_users_product_data['product']->name}}
											</dd>
											<dd>Orange color</dd>
										</dl> -->
									</figcaption>
								</figure>
							</td>
							
							<td>
								<form method="POST" action="{{route('cart.update',[ $id, $key])}}">
										@csrf
									<input type="number" name="qty" id="qty" class="form-control text-center" min="0" max="99" value="{{$product_with_ids_array[$key]['qty']}}">
									<input type="submit" value="Update" class="btn btn-block btn-outline-success btn-round">
								</form>
								
							</td>
							
							<td>
								<div class="price-wrap">
									<span class="price"><p>PKR {{$product_with_ids_array[$key]['price']}}</p></span>

									<small classs="text-muted">({{$product_with_ids_array[$key]['product']->Sale_price}} PKR each)</small>
								</div><!-- price-wrap.//-->
							</td>

							<td class="text-right">
								<form action="{{route('cart.remove',[ $id, $key])}}" method="POST" accept-charset="utf-8">
									@csrf
									<button type="submit" class="btn btn-outline-danger"> x Remove</button>
								</form>
							</td>
						</tr>
						@endforeach
					@endforeach
						<tr>
							<th colspan="2">Total Qty: </th>
							<td>{{$cart->getTotalQty()}}</td>
						</tr>
						<tr>
							<th colspan="2">Total Price: </th>
							<td>{{$cart->getTotalPrice()}}</td>
						</tr>
					</tbody>
				</table>
				<a href="{{route('orderCheckout')}}" class="btn btn-outline-danger">Proceed to Checkout</a>
			</div>
		</div>
	</div>
	@else 
		<div class="col-md-12">
			<p class="alert alert-danger">
				No products in the cart
			</p>
		</div>
	@endif
@endsection