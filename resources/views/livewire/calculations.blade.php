    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
<div> 
   	<div x-data="{ target: '_blank' }">
		<form action="<?php echo route('order.store')?>" method="POST" onkeydown="return event.key != 'Enter';" :target="target">
			@CSRF
		    <div class="container mt-2">
		    	<div class="row">
		    		<div class="col-md-12 px-0">
				    	@if(Session::has('alert-success'))
							<div class="flash-message">	
			     				<p class="alert alert-success">{{ Session::get('alert-success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
			     			</div>
		  				@endif
		    		</div>
		    	</div>
		  		<div class="row pb-3" x-data="{toggle: false, name: '', reset_product_inputs: ''}">
		  			<div class="col-md-6">
		  				<div wire:ignore class="row noPrint mb-2">
		  					    <!-- <div class="flex justify-center items-center"> -->
		  					        <span x-show ="toggle" class=" oblock mr-2 pt-2 text-xs"><b>Search Products By Name:</b></span> 
		  					        <span x-show = "!toggle" class=" block mr-2 pt-2 text-xs"><b>Search Products By Id:</b></span>

		  					        <div class="relative rounded-full w-12 h-6 transition duration-200 ease-linear"
		  					             :class="[toggle ? 'bg-[#1da1f2]' : 'bg-[#3490DC]']">
                                    
		  					            <!-- <label for="toggle"
		  					                   class="absolute drop-shadow-lg left-1 bg-white mt-[2px] w-5 h-5 rounded-full transition transform duration-100 ease-linear cursor-pointer"
		  					                   :class="[toggle ? 'translate-x-full border-black' : 'translate-x-0 border-gray-400']">
		  					            </label>

		  					            <input type="checkbox"
		  					                   id="toggle"
		  					                   name="toggle"
		  					                   x-model="toggle"
		  					                   class="appearance-none w-full h-full active:outline-none focus:outline-none" /> -->
			  					        <label class="switch">
					  					  <input type="checkbox" @click="$refs.p_id.focus(); name=''" id="toggle" name="toggle" x-model="toggle">
					  					  <span class="slider round"></span>
					  					</label>
					  					
		  					        </div>

		  					        <!-- <span class=" block ml-3 text-xs">Business</span> -->
		  					    <!-- </div> -->

		  					    <!-- <div id="toggleOff"
		  					         x-show="!toggle">
		  					        This content shows on page load and toggle is off, otherwise it is hidden
		  					    </div>
		  					    
		  					    <div id="toggleOn"
		  					         x-show="toggle">
		  					        This content shows when the toggle is clicked, otherwise it is hidden
		  					    </div> -->					
		  				</div>
				    	<div class="row">	

						    @livewire('contact-search-bar')
						    <input wire:ignore autocomplete="off" x-model ="name" x-show="toggle" x-ref="p_id" class="mr-1 col-md-7 round_input border border-primary noPrint" id="pid" placeholder="Search Products By Id" type="text" name="product_id" wire:model = "find_product_id"/>

						    <!-- <input type="submit" value="submit"> -->
						    <button wire:ignore x-show="toggle" class="col-md-3 btn btn-primary border border-primary round_input cmt noPrint" name="add_product" @click ="$refs.p_id.focus(); name='';" wire:click.prevent="addProduct()">Add Product</button>
						    <!-- Below button was used for product name input validation which is in
						    ContactSearchBar child component. below code is commented and its related code
						    is also commented in relavent -->
							<!-- <button x-show="!toggle" class="col-md-3 btn btn-primary border border-primary round_input cmt noPrint" name="add_product" @click ="$refs.p_name.focus(); name='';" wire:click.prevent="product_name_field_validation()">Add Product</button> -->
							
						    @if($calProducts)
						    	<!-- <button type="submit" class="btn btn-primary col-md-10 mt-2 mb-2 round_input noPrint">Proceed & Store Order</button> -->
								<button type="submit" name="print" value="print" id="print" class="btn btn-primary col-md-5 mt-2 mb-2 mr-1 round_input noPrint">Print</button>
								<!-- <a href="<?php //echo route('order.print', '2')?>" class="btn btn-primary col-md-5 mt-2 mb-2 mr-1 round_input noPrint">Print</a> -->
								<button x-on:click="target = ''" type="submit" name="storeOrder" value="storeOrder" id="storeOrder" class="btn btn-primary col-md-5 mt-2 mb-2 round_input noPrint">Store Order</button>
						    @endif
				    	</div>
		  			</div>
		  			<div class="col-md-6">
		  				<div class="row mt-1 noPrint">
		  					<label class="col-md-5 col-lg-4 c_label_padding" for="totalPercentage">Total Percentage</label>
		  					<input required="required" class="col-md-7 col-lg-8 round_input border border-primary" type="text" id="totalPercentage" wire:keyup = "discountTotalPercentage()" wire:model = "total_Percentage">
		  				</div>
		  				<div class="row mt-1 noPrint">
		  					<label class="col-md-5 col-lg-4 c_label_padding" for="totalProfit">Total Profit </label>
		  					<input class="col-md-7 col-lg-8 round_input border border-primary" readonly type="text" name="TotalProfit" id="totalProfit" wire:model = "total_Profit">
		  				</div>
		  				<h1 id="h_display" class="d-none">Zaira Crockery</h1>
		  				
		  				<div class="row mt-1">
		  					<label class="col-md-5 col-lg-4 c_label_padding printBoldFont" for="GrandTotal">Grand Total</label>
		  					<input class="col-md-7 col-lg-8 round_input border border-primary noBorderInput" type="text" name="GrandTotal" id="GrandTotal" wire:keyup ="discountGranttotal()" wire:model = "Grand_Total">
		  				</div>
		  			</div>
		  		</div>
		    </div>
		<!-- </form> -->
		<!-- <form action="<?php //echo route('order.store')?>" method="POST"> -->
		    {{--form should come here--}}
			<!-- @CSRF -->
		    <div>
	            @if (session()->has('lowStock'))
		            <div class="alert alert-danger alert-block noPrint container">
		                   <strong>{{ session('lowStock') }}</strong>
	            	</div>
	            @endif
	        </div>
	        @error('query') 
			    <div class="alert alert-danger alert-block container">
			    	<strong>{{ $message }}</strong>
			    </div> 
			@enderror
			@error('total_Percentage') 
			    <div class="alert alert-danger alert-block container">
			    	<strong>{{ $message }}</strong>
			    </div> 
			@enderror
		    @error('find_product_id') 
			    <div class="alert alert-danger alert-block container">
			    	<strong>{{ $message }}</strong>
			    </div> @enderror
		    @if($product_not_exists)
		    	<div class="alert alert-danger alert-block container">
				   <button type="button" wire:click = "alertDismiss()" class="close" data-dismiss="">×</button>   
				   <strong>This product does not exist</strong>
				</div>
		    @endif

		   
		    @if($stock_end):
		    	<div class="alert alert-danger alert-block container">
				  <!--  <button type="button" wire:click = "alertDismiss()" class="close" data-dismiss="alert">×</button>   --> 
				   <strong>This product is out of stock</strong>
				</div>
		    @endif


		    <!-- @error('total_Percentage') <div class="alert alert-danger">{{ $message }}</div> @enderror -->
		    <!-- @error('Grand_Total') <div class="alert alert-danger">{{ $message }}</div> @enderror -->

		    @if($alert)
				<div class="alert alert-danger alert-block noPrint container">
				   <button type="button" wire:click = "alertDismiss()" class="close" data-dismiss="alert">×</button>   
				   <strong>Following product has already been added.</strong>
				</div>
			@endif
			<div class="container">
				@if(!$calProducts)
				<hr>
				@endif
				@if($calProducts)
				<div class="row">
					<div class="table-responsive">
					    <table class="table table-hover table-striped">
						  	<thead class="thead-info">
						    	<tr>
							      <th scope="col" class="noPrint">Id</th>
							      <th scope="col">Product Name</th>
							      <th scope="col" class="noPrint">PP</th>
							      <th scope="col" class="noPrint">Image</th>
							      <th scope="col">Quantity</th>
							      <th scope="col">Rate</th>
							      <th scope="col">Total Price</th>
							      <th scope="col" class="noPrint">Percentage</th>
							      <th scope="col" class="noPrint">Profit</th>
						    	</tr>
						  	</thead>
						  	<tbody>	
						  		@foreach($calProducts as $products)

							  	<tr <?php if($existed_product_id == $products['id']) echo'class="table-warning"'?>>
							  		<td class="first_col_width noPrint">

							  			<input style="width:40px !important" required="required" class="round_input noBorderInput border border-primary border-0" readonly type="text" name="product_id[]" value="{{$products['id'] ?? ''}}">
							  			
							  			
							  		</td>
							  		<td>{{$products['name'] ?? ""}}</td>
							  		<input required="required" hidden type="text" name="product_name[]" value="{{$products['name'] ?? ''}}">
							  			

							  		<td class="noPrint">{{$products['purchase_price'] ?? ""}}</td>
							  		@php
							  			$index = $loop->iteration -1;
							  		@endphp

									<td class="noPrint product_image_container align-middle">
										<img class="product_image" src="{{ asset($products['img_upload_url']) }}" alt="" title="" name="product_image">

									</td>
						    		<!-- <input type="text" wire:model="Product_qty"> -->
						    		
							  		<td class="custom_col_width"><input
							  		@error('Product_qty.'.$index)
							  			autofocus
							  		@enderror
							  		required="required"
							    		@if($errors->has('*'))

							  				@if(!$errors->has('Product_qty.'.$index))
									        	{{ 'readonly' }}
							    			@endif
									    @endif
									    class="round_input noBorderInput border border-primary w-100 small_screen_input_width " type="text" name="qty[]" 
									    
									    	wire:keyup="set_quantity({{$index}})" wire:model = "Product_qty.{{$index}}" >
									</td>
									

									

						    		@error('Product_qty.'.$index) 
							    		<div class="alert alert-danger">
							    			<strong>
							    				{{ $message }}	
							    			</strong>
							    		</div>
						    		@enderror


							  		<td class="custom_col_width"><input 
							  			@if($errors->has('*'))

							  				@if(!$errors->has('Rate.'.$index))
									        	{{ 'readonly' }}
							    			@endif
									    @endif
							  			class="round_input noBorderInput border border-primary w-100 small_screen_input_width" type="text" name="Rate[]" wire:keyup="updateRate({{$index}})" wire:model = "Rate.{{$index}}" ></td>

							  		@error('Rate.'.$index) <div class="alert alert-danger">
							  			
							  		{{ $message }}</div> @enderror

							  		<td class="custom_col_width"><input 
							  			@if($errors->has('*'))

							  				@if(!$errors->has('Total_Price.'.$index))
									        	{{ 'readonly' }}
							    			@endif
									    @endif
							  			class="round_input border border-primary noBorderInput printBoldFont w-100 small_screen_input_width" type="text" readonly wire:model = "Total_Price.{{round($index,2)}}"></td>
							  			
							  		@error('Total_Price.'.$index) <div class="alert alert-danger">{{ $message }}</div> @enderror

							  		<td class="custom_col_width"><input 
										@if($errors->has('*'))

							  				@if(!$errors->has('input_percentage.'.$index))
									        	{{ 'readonly' }}
							    			@endif
									    @endif
							  			class="round_input border border-primary noPrint w-100 small_screen_input_width" type="text" wire:keyup ="updateProfit({{$index}})" wire:model = "input_percentage.{{$index}}" ></td>

							  		@error('input_percentage.'.$index)
							  		<div class="alert alert-danger">
							  			<strong>
							  				{{ $message }}
							  			</strong>
							  		</div> 
							  		@enderror

							  		@error('Grand_Total')
							  		<div class="alert alert-danger">
							  			<strong>
							  				{{ $message }}
							  			</strong>
							  		</div> 
							  		@enderror

							  		<td class="noPrint">{{$profit[$index]}}</td>
							  	</tr>
						  		@endforeach
						  	</tbody>
						</table>
					</div>
				</div>
				@endif	
			</div>
	    </form>
    </div>
</div>

	<!-- <script>
		document.getElementById("pid").focus();
	</script> -->