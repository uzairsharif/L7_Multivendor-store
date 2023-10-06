<div x-show="!toggle" class="col-lg-7 mr-1 px-lg-0" style="position:relative;">
    <input style="height: 37px"
        name = ""
        x-model="name"
        {{--x-ref="p_name"--}}
        autocomplete="off" {{--input ma suggestions dropdown na da on type--}}
        id="pid"
        type="text"
        class="mr-1 col-md-12 round_input border border-primary noPrint"
        placeholder="Search Products By Name"
        wire:model="query"
        wire:keydown.escape="resett"
        wire:keydown.tab="resett"
        wire:keydown.arrow-up="decrementHighlight"
        wire:keydown.arrow-down="incrementHighlight"
        {{--wire:keydown.enter="selectContact"--}}
    />
 
    <div wire:loading class="z-3 w-full bg-white rounded-t-none shadow-lg list-group" style="position:absolute; width:100%">
        <div style="z-index:9; background: white" class="list-item">Searching...</div>
    </div>
    <!-- @error('query')
        <div class="alert alert-danger alert-block container">
            <strong>{{ $message }}</strong>
        </div>
    @enderror -->
    
    @if($show_products_dropdown)
        @if(!empty($query))

            <div x-data="{MenuOpen: true}">
                
                <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="resett"></div>
         
                <div @click.away="MenuOpen = false" x-show="MenuOpen" @keyup.down="incrementHighlight" @keyup.up="decrementHighlight" style="position: absolute; background: white; width:100%; z-index:10;" class="shadow-lg list-group px-2 py-1 rounded">
                    @if(!empty($searched_products))
                        @foreach($searched_products as $i => $searched_product)
                            <!-- <a wire:click="$emit('Set_Product_Id_Emit',{{$searched_product['id']}})" -->
                                <a wire:click="set_name('{{$searched_product['id']}}', '{{$searched_product['name']}}')"
                               href="#"
                                {{--class="list-item {{ $highlightIndex === $i ? 'highlight' : '' }}"--}}
                                class = "text-decoration-none search-box-suggestion-link {{ $highlightIndex === $i ? 'highligh' : '' }}"
                                {{-- ooper highlight k t ma na gum kia kiun k jb highlight class milti thi arrow ki down ya up press krnay sa pr hover pr bhi hightligh ho rha hai to is ko bad ma dekhna hai. wesay arrow ki ka php wla backend code theek kam kr rha hai. --}}
                            >{{ $searched_product['name'] }}</a>
                        @endforeach
                    @else
                        <div class="list-item">No results!</div>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>