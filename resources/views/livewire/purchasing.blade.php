<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
          {{ session('message') }}
        </div>
    @endif
    @if (session()->has('invalidId'))
        <div class="alert alert-danger">
          {{ session('invalidId') }}
        </div>
    @endif
    <form>
    	<h1>Purchasing Form</h1>
        <div class=" add-input">
            <div class="row">
                <div x-data="{showInput0: false}" class="col-md-3">
                    <div class="form-group">
                        <select x-show="!showInput0" class="form-control" wire:model="product_id.0" wire:change="update_row(0)" name="" id="">
                            <option value="0">select existing products</option>
                            @foreach($my_products as $my_product)
                                <option value="{{$my_product->id}}">{{ $my_product->name }}</option>            
                            @endforeach;    
                        </select>
                        <input x-show="showInput0" type="text" class="form-control" placeholder="Product ID" wire:keydown.space="update_row(0)" wire:model="product_id.0">
                         <button @click="showInput0 = !showInput0 , $wire.set_productId(0)" x-show="!showInput0" type="button" class="btn btn-outline-primary btn-sm">Add New</button>
                         <button @click="showInput0 = !showInput0" x-show="showInput0" type="button" class="btn btn-outline-primary btn-sm">Drop Down</button>
                        @error('product_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Product Name" wire:model="product_name.0">
                        @error('product_name.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Product Description" wire:model="product_description.0">
                        @error('product_description.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>             
                <div class="col-md-3">
	            	<div class="form-group">            		 
	            	    <select name="product_category" class="form-control" wire:model="product_category_id.0" placeholder="Select Product Category">
	            	    	<option value="">Select Product Category</option>
	            	    	@foreach($categories as $value)
	            	     	<option value="{{$value->id}}">					{{$value->category_name}}
	            	     	</option>
	            	     	@endforeach
	            	    </select>                       
                        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#exampleModal">Add Category</button>
	            	    @error('product_category_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
	              	</div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" wire:model="product_qty.0" placeholder="Product Quantity">
                        @error('product_qty.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" wire:model="product_purchase_rate.0" placeholder="Purchase Rate">
                        @error('product_purchase_rate.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group m-0">
                        <input type="text" class="form-control" wire:model="product_sale_price.0" placeholder="Sale price">
                        @error('product_sale_price.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <button type="button" wire:click="update_sale_price(0)" class="btn btn-outline-primary btn-sm">Get 40% of purchase price</button>
                </div>
                <div class="col-md-3">
    			  	<div class="form-group">
    			    	<input type="file" class="form-control file" name="product_image" wire:model="product_image.0">
    			    	@error('product_image.0') <span class="text-danger error">{{ $message }}</span>@enderror
    			  	</div>
                </div>

                
            </div>
        </div>
        <hr>
        <hr>
    
        @foreach($inputs as $key => $value)
            <div class=" add-input">
                <div class="row">
                    <div x-data="{showInput{{$key+1}}: false}" class="col-md-3">
                        <div class="form-group">
                            <select x-show="!showInput{{$key+1}}" class="form-control" wire:model="product_id.{{$key+1}}" wire:change="update_row({{$key+1}})" name="" id="">
                            <option value="0">select existing products</option>
                            @foreach($my_products as $my_product)
                                <option value="{{$my_product->id}}">{{ $my_product->name }}</option>            
                            @endforeach;    
                        </select>
                            <input x-show="showInput{{$key+1}}"type="text" class="form-control" placeholder="Product ID" wire:keydown.space="update_row({{$value}})" wire:model="product_id.{{ $value }}">
                            <button @click="showInput{{$key+1}} = !showInput{{$key+1}} , $wire.set_productId({{$key+1}})" x-show="!showInput{{$key+1}}" type="button" class="btn btn-outline-primary btn-sm">Add New</button>
                         <button @click="showInput{{$key+1}} = !showInput{{$key+1}}" x-show="showInput{{$key+1}}" type="button" class="btn btn-outline-primary btn-sm">Drop Down</button>
                            @error('product_id.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-3">
	                    <div class="form-group">
	                        <input type="text" class="form-control" placeholder="Product Name" wire:model="product_name.{{ $value }}">
	                        @error('product_name.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
	                    </div>
	                </div>
	                <div class="col-md-3">
	                    <div class="form-group">
	                        <input type="text" class="form-control" placeholder="Product Description" wire:model="product_description.{{ $value }}">
	                        @error('product_description.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
	                    </div>
	                </div>             
	                <div class="col-md-3">
		            	<div class="form-group">            		 
		            	    <select name="product_category" class="form-control" wire:model="product_category_id.{{$value}}" placeholder="Select Product Category">
		            	    	<option value="0">Select Product Category</option>
		            	    	@foreach($categories as $category)
		            	     	<option value="{{$category->id}}">					{{$category->category_name}}
		            	     	</option>
		            	     	@endforeach
		            	    </select>
		            	    @error('product_category_id.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
		              	</div>
	                </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" wire:model="product_qty.{{ $value }}" placeholder="Product Quantity">
                            @error('product_qty.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" wire:model="product_purchase_rate.{{ $value }}" placeholder="Purchase Rate">
                            @error('product_purchase_rate.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group m-0">
                            <input type="text" class="form-control" wire:model="product_sale_price.{{ $value }}" placeholder="Sale price">
                            @error('product_sale_price.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <button type="button" wire:click="update_sale_price({{$value}})" class="btn btn-outline-primary btn-sm">Get 40% of purchase price</button>
                    </div>
                    <div class="col-md-3">
    			  		<div class="form-group">
    			    		<input type="file" class="form-control file" name="product_image" wire:model="product_image.{{ $value }}">
    			    		@error('product_image.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
    			  		</div>
                	</div>
                    <div class="col-md-3">
                        <button class="btn btn-danger btn-sm" wire:click.prevent="remove({{$key}})">X Remove</button>
                    </div>
                </div>
            </div>
            <hr>
            <hr>
        @endforeach
    
        <div class="row">
            <div class="col-md-1">
                <button type="button" wire:click.prevent="store()" class="btn btn-success btn-sm">Submit</button>
            </div> 
            <div class="col-md-3">
                <button class="btn text-white btn-info btn-sm" wire:click.prevent="add({{$i}})">Add New Product</button>
            </div>
        </div>
    
    </form>
    @include('livewire.bootstrap-modal')
</div>
