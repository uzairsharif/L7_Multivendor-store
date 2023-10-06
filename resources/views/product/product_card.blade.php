@extends('layouts.guest')
@section('title', 'Card_Product')
@section('content')
	<!-- <div x-data="setup()">
	    <span x-text="shoutyGreeting"></span>
	</div>
	 
	<script>
	    function setup () {
	        return {
	            greeting: 'Hello there',
	 
	            get shoutyGreeting () {
	                return `${this.greeting.toUpperCase()}!`
	            }
	        }
	    }
	</script> -->
	<div class="position-relative container pt-4 pb-0" 
		x-init = "() => toggle()"
        x-data="{ 
            search: '',
            dropdown_menu:false,
            last_item_index: '',
            items: [],
            toggle(){
            	var items_array = {{@json_encode($all_categories)}};
            	items_array.push('All Products');
            	this.items = items_array.map(element => element.toLowerCase());
            	this.last_item_index = items_array.length - 1;
            	var last_item_index = this.last_item_index;
            	console.log(last_item_index);
            	console.log(items_array);
        	},
            get searchResults () {
                return this.items.filter(
                    i => i.startsWith(this.search.toLowerCase())
                )
            } 
        }">
        <div style="padding-left:15px; padding-right:15px" class="row">
        	<div class="col-sm-12 p-0"> 		
		        <!-- Search input -->
		        <input x-on:click.outside="dropdown_menu = false" x-on:focus="dropdown_menu = !dropdown_menu" type="search" class="form-control w-100" placeholder="Search Products By Category" x-model="search">
		        
		        <!-- List items -->
		        <ul x-show="dropdown_menu"  style="height:150px;z-index:2;"class="list-group position-absolute w-100 overflow-auto">
		          	<!-- <li x-ref="all_products" class="list-group-item"><a href="{{ route('products_page')}}">All Products</a></li> -->
		          	<template x-for="(item, index) in searchResults">		        <div>	
			          			<li x-show="index==last_item_index" class="list-group-item"><a x-text="item" href="{{ route('products_page')}}"></a></li>
			          			<li x-show="index!=last_item_index" class="list-group-item"><a x-bind:href="'/product_category/' + item" x-text="item"></a></li>
			          		</div>	          		          				
		          	</template>
		        </ul>
        	</div>
        </div>
	</div>
	<!-- Below is the code for sidebar menu for product categories which I made again as made above with search option. -->
	<!-- <div class="categories_sidebar">

		<h3>categories</h3>
		<div class="drop_down_container" x-data="{open:false}">
			<button x-on:click="open= !open">categories <i class="fa fa-caret-down" style="font-size:24px"></i></button>
			<div style="display:none" class="drop_down_items_container" x-show="open">
				<a href="{{ route('products_page')}}">all products</a>
				@foreach($all_categories as $category_name)
					<a href="{{ route('categorized_products', $category_name) }}">{{$category_name}}</a>
			@endforeach
			</div>
		</div>
	</div> -->
	<div class="container">
	@if(session()->has('message'))
		<div class="row">
			<div class="col-sm-12">
				<p class="alert alert-success">
					{{ session()->get('message') }}
				</p>
			</div>
		</div>
	@endif
		<div class="row">
			
			<div class="col-sm-12">
				
				<form method="post" action="{{ route('order.DoCheckout') }}">
					@CSRF
					@php $count = 1; @endphp
					@foreach($products as $product)

						@if($count == 1)
							<div class="row mt-5 mb-4">
								<div class="col-sm-4">
								  	<div class="card" style="width:280px; height:446px;">
								  		<div style="overflow:hidden; width:279px; height:444px;" class="card_container">
								    	<img class="card-img-top w-100 h-100" src="{{ asset($product->img_upload_url) }}" alt="Card image cap">
								  		</div>
								    	<div class="card-body mb-3 text-center">
								      		<h5 class="card-title hover-bg">Name: {{ $product->name }}</h5>
								      		<h5 class="card-title hover-bg">Price: {{ $product->Sale_price }}</h5>
								      		<h5 class="card-title hover-bg">Available: {{ $product->stock }}</h5>
								    	</div>
							      		<a type="button" class="mb-1 btn btn-sm btn-secondary" href=" {{route('product.show', $product->id)}}">View Product</a>

							      		<a type="button" href="{{ route('product.addToCart',[$product->id,$product->user_id])}}" class="btn btn-sm btn-secondary">Add to Cart</a>
								    		<!-- <input type="button" class="btn-info" value="Add to cart"> -->
								    	<!-- <div class="card-footer">
								      		<small class="text-muted">Last updated: {{ $product->updated_at }}</small>
								    	</div> -->
								  	</div>
								</div>
						@elseif($count == 2)
								<div class="col-sm-4">
								  	<div class="card" style="width:280px; height:446px; margin:0 auto;">
								    	<div style="overflow:hidden; width:279px; height:444px;" class="card_container">
								    	<img class="card-img-top w-100 h-100" src="{{ asset($product->img_upload_url) }}" alt="Card image cap">
								  		</div>
								    	<div class="card-body mb-3 text-center">
								      		<h5 class="card-title hover-bg">Name: {{ $product->name }}</h5>
								      		<h5 class="card-title hover-bg">Price: {{ $product->Sale_price }}</h5>
								      		<h5 class="card-title hover-bg">Available: {{ $product->stock }}</h5>
								    	</div>
							      		<a type="button" class="mb-1 btn btn-sm btn-secondary" href=" {{route('product.show', $product->id)}}">View Product</a>
							      		<a type="button" href="{{ route ('product.addToCart',[$product->id,$product->user_id])}}" class="btn btn-sm btn-secondary">Add to Cart</a>
								    	<!-- <div class="card-footer">
								      		<small class="text-muted">Last updated: {{ $product->updated_at }}</small>
								    	</div> -->
								  	</div>
								</div>
						@elseif($count == 3)
								<div class="col-sm-4">
								  	<div class="card" style="width:280px; height:446px; float:right">
								    	<div style="overflow:hidden; width:279px; height:444px;" class="card_container">
								    		<img class="card-img-top w-100 h-100" src="{{ asset($product->img_upload_url) }}" alt="Card image cap">
								  		</div>
								    	<div class="card-body mb-3 text-center">
								      		<h5 class="card-title hover-bg">Name: {{ $product->name }}</h5>
								      		<h5 class="card-title hover-bg">Price: {{ $product->Sale_price }}</h5>
								      		<h5 class="card-title hover-bg">Available: {{ $product->stock }}</h5>
								    	</div>
							      		<a type="button" class="mb-1 btn btn-sm btn-secondary" href=" {{route('product.show', $product->id)}}">View Product</a>
							      		<a type="button" href="{{ route('product.addToCart',[$product->id,$product->user_id])}}" class="btn btn-sm btn-secondary">Add to Cart</a>
								    	<!-- <div class="card-footer">
								      		<small class="text-muted">Last updated: {{ $product->updated_at }}</small>
								    	</div> -->
								  	</div>
								</div>
							</div> 
							<hr>    
							<!-- end of row -->
						@endif
						@php
							$count++;
							if($count == 4)
							$count = 1;
						@endphp
					@endforeach
					
				</form>
			</div>
		</div>
	</div>
<!-- this above div closure is for container end -->
@endsection