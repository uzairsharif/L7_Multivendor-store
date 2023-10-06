<?php
 
namespace App\Http\Livewire;
 
use App\Product;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\DB;
 
class ContactSearchBar extends Calculations
{
    public $query;
    public $searched_products;
    public $highlightIndex;
    public $show_products_dropdown;
    public $user_id;
    // protected $listeners = ['product_name_field_validation' => 'productNameFieldValidation'];
    public function __construct(){
        $this->user_id = Auth::user()->id;
    }
    public function mount()
    {
        
        $this->resett();
    }

    public function resett()
    {

        $this->query = '';
        $this->contacts = [];
        $this->highlightIndex = 0;
    }
    // below method was called from Calculatioons file from product_name_field_validation
    // public function productNameFieldValidation(){

    //     $validator = Validator::make(
    //            [
    //                'query'  => $this->query,
                   
    //            ],
    //            [
    //                'query'  => 'required',
    //            ]
    //        );

    //        if ($validator->fails()) {
    //            // session()->flash('custom_message', 'Oops! Something went wrong...');
    //             $message = "Product name field is required";
    //             $this->emit('product_name_validation_Emit', $message);
    //        } 
           
    //        $validator->validate();
    //        $this->emit('call_addProduct');
    // }
    public function set_name($product_id , $product_name){
        
        $this->query = $product_name;
        $this->emit('Set_Product_Id_Emit',$product_id);
        $this->show_products_dropdown = false;
        // $emit('Set_Product_Id_Emit',{{$contact['id']}}) 
    }
    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->contacts) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }
 
    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->contacts) - 1;
            return;
        }
        $this->highlightIndex--;
    }
 
    // public function selectContact()
    // {
    //     $contact = $this->contacts[$this->highlightIndex] ?? null;
    //     if ($contact) {
    //         $this->redirect(route('show-contact', $contact['id']));
    //     }
    // }
    
    public function updatedQuery()
    {

        $this->show_products_dropdown = true;

        // $this->searched_products = Product::where('name', 'like', '%' . $this->query . '%')
        //     ->where('user_id', $this->user_id)
        //     ->select('id','name')
        //     ->get()
        //     ->toArray();
        // var_dump($this->searched_products);
        $this->searched_products = DB::table('purchase_body')
        ->rightjoin('products',function($join){$join
            ->on('purchase_body.product_id', '=', 'products.id')
            ->on('purchase_body.user_id','=','products.user_id');})
            ->where('name','like', '%'.$this->query.'%')
            ->where('products.user_id', $this->user_id)
            ->where('products.deleted_at', Null)
            ->select('product_id as id', 'products.name as name')
            ->distinct()
            ->orderby('name','asc')
            ->get()
            ->toArray();
        
        //idhar distinct nhi tha pehlay aur select k andar purchase_body.product_purchase_qty bhi shamil tha.
        if($this->searched_products){
        foreach($this->searched_products as $object)
            {
                $arrays[] =  (array) $object;
                
            }

            $this->searched_products = $arrays;
        }
    }
    
    public function render()
    {
        return view('livewire.contact-search-bar');
        
    }
}