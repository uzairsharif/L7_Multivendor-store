<div>
    <div class="card">
          <div class="card-header">
            Laravel Livewire Delete Confirmation Example - ItSolutionStuff.com
          </div>
  
          <div class="card-body">
        
            <table class="table-auto" style="width: 100%;">
              <thead>
                <tr>
                  <th class="px-4 py-2">ID</th>
                  <th class="px-4 py-2">Name</th>
                  <th class="px-4 py-2">Description</th>
                  <th class="px-4 py-2">Quantity</th>
                  <th class="px-4 py-2">Purchase Price</th>
                  <th class="px-4 py-2">Sale Price</th>
                  <th class="px-4 py-2">Created At</th>
                  <th class="px-4 py-2">Updated At</th>

                  <th class="px-4 py-2">Action</th>
                </tr>
              </thead>
              <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="border px-4 py-2">{{ $product->id }}</td>
                            <td class="border px-4 py-2">{{ $product->name }}</td>
                            <td class="border px-4 py-2">{{ $product->description }}</td>
                            <td class="border px-4 py-2">{{ $product->stock }}</td>
                            <td class="border px-4 py-2">{{ $product->purchased_price }}</td>
                            <td class="border px-4 py-2">{{ $product->Sale_price }}</td>
                            <td class="border px-4 py-2">{{ $product->created_at }}</td>
                            <td class="border px-4 py-2">{{ $product->updated_at }}</td>
                            <td class="border px-4 py-2">
                                <button type="button" wire:click="deleteId({{ $product->id }})" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">Delete</button>
                               
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary" role="button">Update</a>
                            </td>
                        </tr>
                    @endforeach
              </tbody>
            </table>
  
            <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-dismiss="modal">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
</div>