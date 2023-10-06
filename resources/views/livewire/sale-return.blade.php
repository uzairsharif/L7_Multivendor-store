<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
          {{ session('message') }}
        </div>
    @endif
  
    <form>
    	<h1>Sale Return</h1>
        <div class=" add-input">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Product ID" wire:model="product_id.0">
                        @error('product_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" wire:model="product_qty.0" placeholder="Return Quantity">
                        @error('product_qty.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control" wire:model="product_return_rate.0" placeholder="Return Rate">
                        @error('product_return_rate.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <button class="btn text-white btn-info btn-sm" wire:click.prevent="add({{$i}})">Add</button>
                </div>
            </div>
        </div>
  
        @foreach($inputs as $key => $value)
            <div class=" add-input">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Product ID" wire:model="product_id.{{ $value }}">
                            @error('product_id.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" wire:model="product_qty.{{ $value }}" placeholder="Return Quantity">
                            @error('product_qty.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" wire:model="product_return_rate.{{ $value }}" placeholder="Return Rate">
                            @error('product_return_rate.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-danger btn-sm" wire:click.prevent="remove({{$key}})">Remove</button>
                    </div>
                </div>
            </div>
        @endforeach
  
        <div class="row">
            <div class="col-md-12">
                <button type="button" wire:click.prevent="store()" class="btn btn-success btn-sm">Submit</button>
            </div>
        </div>
  
    </form>
</div>