@extends('layouts.guest')
@section('title', 'Checkout')
@section('content') 
<div style="margin-top:10px;" class="container">
	
	<div class="row">
	        <div class="col-md-4 order-md-2 mb-4">
	          <h4 class="d-flex justify-content-between align-items-center mb-3">
	            <span class="text-muted">Your cart</span>
	            <span class="badge badge-secondary badge-pill">{{$cart->getTotalQty()}}</span>
	          </h4>
	          <ul class="list-group mb-3">
	          	@foreach($cart->getContents() as $id => $product_with_ids_array)
	          		@foreach($product_with_ids_array as $key => 				$single_users_product_data)
	          	 
			          	<li class="list-group-item d-flex justify-content-between lh-condensed">
			          		<div>
			          			<h6 class="my-0">{{$single_users_product_data['product']->name}}</h6>
			          			<small class="text-muted">{{$product_with_ids_array[$key]['qty']}}</small>
			          		</div>
			          		<span class="text-muted">{{$product_with_ids_array[$key]['price']}}</span>
			          	</li>
			        @endforeach  	
	            @endforeach
	            <li class="list-group-item d-flex justify-content-between">
	              <span>Total (USD)</span>
	              <strong>{{$cart->getTotalPrice()}}</strong>
	            </li>
	          </ul>
	        </div>
	        <div class="col-md-8 order-md-1">
	          <h4 class="mb-3">Billing address</h4>
	          <form class="needs-validation" novalidate="" action="{{route('checkout.stripe_checkout')}}" method="post">
	          	@csrf
	            <div class="row">
	              <div class="col-md-6 mb-3">
	              	<input type="hidden" name="cart_total" value="{{$cart->getTotalPrice()}}">
	                <label for="firstName">First name</label>
	                <input name="billing_firstName" type="text" class="form-control" id="firstName" placeholder="" value="" required="">
	                @if($errors->has('billing_firstName'))
                		<div class="alert alert-danget">
                			{{$errors->first('billing_firstName')}}
                		</div>
                	@endif
	                
	              </div>
	              <div class="col-md-6 mb-3">
	                <label for="lastName">Last name</label>
	                <input name="billing_lastName" type="text" class="form-control" id="lastName" placeholder="" value="" required="">
	                @if($errors->has('billing_lastName'))
                		<div class="alert alert-danget">
                			{{$errors->first('billing_lastName')}}
                		</div>
                	@endif
	                
	              </div>
	            </div>

	            <div class="mb-3">
	              <label for="username">Username</label>
	              <div class="input-group">
	                <div class="input-group-prepend">
	                  <span class="input-group-text">@</span>
	                </div>
	                <input name="username" type="text" class="form-control" id="username" placeholder="Username" required="">
	                @if($errors->has('username'))
                		<div class="alert alert-danget">
                			{{$errors->first('username')}}
                		</div>
                	@endif
	                
	                
	              </div>
	            </div>

	            <div class="mb-3">
	              <label for="email">Email <span class="text-muted">(Optional)</span></label>
	              <input name="email" type="email" class="form-control" id="email" placeholder="you@example.com">
	              @if($errors->has('email'))
                		<div class="alert alert-danget">
                			{{$errors->first('email')}}
                		</div>
                	@endif
	                
	            </div>

	            <div class="mb-3">
	              <label for="address">Address</label>
	              <input name="billing_address1" type="text" class="form-control" id="address" placeholder="1234 Main St" required="">
	              @if($errors->has('billing_address1'))
                		<div class="alert alert-danget">
                			{{$errors->first('billing_address1')}}
                		</div>
                	@endif
	                
	            </div>

	            <div class="mb-3">
	              <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
	              <input name="billing_address2" type="text" class="form-control" id="address2" placeholder="Apartment or suite">
	              @if($errors->has('billing_address2'))
                		<div class="alert alert-danget">
                			{{$errors->first('billing_address2')}}
                		</div>
                	@endif
	                
	            </div>

	            <div class="row">
	              <div class="col-md-5 mb-3">
	                <label for="country">Country</label>
	                <select name="billing_country" class="custom-select d-block w-100" id="country" required="">
	                  <option value="">Choose...</option>
	                  <option>United States</option>
	                </select>
	                @if($errors->has('billing_country'))
                		<div class="alert alert-danget">
                			{{$errors->first('billing_country')}}
                		</div>
                	@endif
	                
	                
	              </div>
	              <div class="col-md-4 mb-3">
	                <label for="state">State</label>
	                <select name="billing_state" class="custom-select d-block w-100" id="state">
	                  <option value="">Choose...</option>
	                  <option>California</option>
	                </select>
	                @if($errors->has('billing_state'))
                		<div class="alert alert-danget">
                			{{$errors->first('billing_state')}}
                		</div>
                	@endif
	                
	                
	              </div>
	              <div class="col-md-3 mb-3">
	                <label for="zip">Zip</label>
	                <input name="billing_zip" type="text" class="form-control" id="zip" placeholder="" required="">
	                @if($errors->has('billing_zip'))
                		<div class="alert alert-danget">
                			{{$errors->first('billing_zip')}}
                		</div>
                	@endif
	                
	              </div>
	            </div>
	            <hr class="mb-4">
		        <div x-data="{ show: true }">
		            <div class="custom-control custom-checkbox">
		              <input @click="show = !show" type="checkbox" class="custom-control-input" id="same-address">
		              <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
		            </div>
		            <div class="custom-control custom-checkbox">
		              <input type="checkbox" class="custom-control-input" id="save-info">
		              <label class="custom-control-label" for="save-info">Checkout as a Guest</label>
		            </div>
		            <hr class="mb-4">
		        	<div x-show="show" id="shipping_address" class="col-md-12 order-md-1">
						<h4 class="mb-3">Shipping address</h4>
			            <div class="row">
			              <div class="col-md-6 mb-3">
			                <label for="firstName">First name</label>
			                <input name="shipping_firstName" type="text" class="form-control" id="firstName" placeholder="" value="" required="">
			                @if($errors->has('shipping_firstName'))
                		<div class="alert alert-danget">
                			{{$errors->first('shipping_firstName')}}
                		</div>
                	@endif
	                
			              </div>
			              <div class="col-md-6 mb-3">
			                <label for="lastName">Last name</label>
			                <input name="shipping_lastName" type="text" class="form-control" id="lastName" placeholder="" value="" required="">
			                @if($errors->has('shipping_lastName'))
                		<div class="alert alert-danget">
                			{{$errors->first('shipping_lastName')}}
                		</div>
                	@endif
	                
			              </div>
			            </div>
			
			            <div class="mb-3">
			              <label for="address">Address</label>
			              <input name="shipping_address" type="text" class="form-control" id="address" placeholder="1234 Main St" required="">
			              @if($errors->has('shipping_address'))
                		<div class="alert alert-danget">
                			{{$errors->first('shipping_address')}}
                		</div>
                	@endif
	                
			            </div>

			            <div class="mb-3">
			              <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
			              <input name="shipping_address2" type="text" class="form-control" id="address2" placeholder="Apartment or suite">
			              @if($errors->has('shipping_address2'))
                		<div class="alert alert-danget">
                			{{$errors->first('shipping_address2')}}
                		</div>
                	@endif
	                
			            </div>

			            <div class="row">
			              <div class="col-md-5 mb-3">
			                <label for="country">Country</label>
			                <select name="shipping_country" class="custom-select d-block w-100" id="country" required="">
			                  <option value="">Choose...</option>
			                  <option>United States</option>
			                </select>
			                @if($errors->has('shipping_country'))
                		<div class="alert alert-danget">
                			{{$errors->first('shipping_country')}}
                		</div>
                	@endif
	                
			                
			              </div>
			              <div class="col-md-4 mb-3">
			                <label for="state">State</label>
			                <select name="shipping_state" class="custom-select d-block w-100" id="state" required="">
			                  <option value="">Choose...</option>
			                  <option>California</option>
			                </select>
			                @if($errors->has('shipping_state'))
                		<div class="alert alert-danget">
                			{{$errors->first('shipping_state')}}
                		</div>
                	@endif
	                
			                
			              </div>
			              <div class="col-md-3 mb-3">
			                <label for="zip">Zip</label>
			                <input name="shipping_zip" type="text" class="form-control" id="zip" placeholder="" required="">
			                @if($errors->has('shipping_zip'))
                		<div class="alert alert-danget">
                			{{$errors->first('shipping_zip')}}
                		</div>
                	@endif
	                 
			              </div>
			            </div>
			        </div>
			        <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
			    </div>
	          </form>
	        </div>
	      </div>
		</div>

@endsection
@section('scripts')  
   
@endsection

