<?php
  
namespace App\Http\Livewire;
  
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Category;
  
class Purchasing extends Component
{
	use WithFileUploads;
    public $product_id,$product_name,$product_description,$product_category_id, $product_qty, $product_purchase_rate, $product_sale_price,$product_image,$next_purchase_body_id;

    public $category_name, $created_at, $updated_at;

    public $updateMode = false;
    public $inputs = [];
    public $i = 1;
    
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function add($i)
    {
    	// $this->next_purchase_body_id ++;
    	// dd($this->next_purchase_body_id);
        array_push($this->inputs ,$i);
        $i = $i + 1;

        $this->i = $i;
        // $this->product_id[$i] = $this->next_purchase_body_id;
        
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove($i)
    {
        unset($this->inputs[$i]);
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function render()
    {
        // $a = DB::table('products')->select('id','name')->where('user_id',Auth::id())->get();
        // dd($a);
    	if($this->i == 1){

    		$next_id = DB::select(DB::raw('select COALESCE(max(id),0)+1 as next_id from purchase_head')); 
    		$next_id = $next_id[0]->next_id;
            
    		$this->next_purchase_body_id = $next_id;
    	}
        return view('livewire.purchasing', [
            'categories' => DB::table('categories')->select('id','category_name')->get(),
            'my_products' => DB::table('products')->select('id','name')->where('user_id',Auth::id())->get(),          
        ]);
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    private function resetInputFields(){
        $this->product_id = '';
        $this->product_name = '';
        $this->product_description = '';
        $this->product_category_id = '';
        $this->product_qty = '';
        $this->product_purchase_rate = '';
        $this->product_sale_price = '';
        $this->product_image = '';

    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store()
    {
        $validatedDate = $this->validate([
                'product_id.0' => 'required',
                'product_name.0' => 'required',
                'product_description.0' => 'required',
                'product_category_id.0' => 'required',
                'product_qty.0' => 'required',
                'product_purchase_rate.0' => 'required',
                'product_sale_price.0' => 'required',
                'product_image.0' => 'mimes:jpeg,png|max:1014|required',
                'product_id.*' => 'required',
                'product_name.*' => 'required',
                'product_description.*' => 'required',
                'product_category_id.*' => 'required',
                'product_qty.*' => 'required',
                'product_purchase_rate.*' => 'required',
                'product_sale_price' => 'required',
                'product_image.*' => 'mimes:jpeg,png|max:1014|required',
            ],
            [
                'product_id.0.required' => 'Product ID field is required',
                'product_name.0.required' => 'Product name field is required',
                'product_description.0.required' => 'Product description field is required',
                'product_category_id.0.required' => 'Product category field is required',
                'product_qty.0.required' => 'Product Quantity field is required',
                'product_purchase_rate.0.required' => 'Product Purchase Rate field is required',
                'product_sale_price.0.required' => 'Product Sale Price field is required',
                'product_image.0.required' => 'Product image field is required',
                'product_id.*.required' => 'Product ID field is required',
                'product_name.*.required' => 'Product name field is required',
                'product_description.*.required' => 'Product description field is required',
                'product_category_id.*.required' => 'Product category field is required',
                'product_qty.*.required' => 'Product Quantity field is required',
                'product_purchase_rate.*.required' => 'Product Purchase Rate field is required',
                'product_sale_price.*.required' => 'Product Sale Price field is required',
                'product_image.*.required' => 'Product image field is required',
            ]
        );
   		$user_id = Auth::user()->id;   		                          

        DB::insert('insert into purchase_head (payment_amount, vendor_id,created_at, updated_at) values (?,?,?,?)', ['not_inseted_yet','1', now(), now()]);
        $purchase_head_id = DB::getPdo()->lastInsertId();
        foreach ($this->product_id as $key => $value) {
        	// dd($this->product_id[$key]);

        	$extension = $this->product_image[$key]->extension();
        	$custom_product_name = "product_".$this->product_id[$key].".$extension";
        	
        	$custom_folder_name = "user_$user_id";
        	$this->product_image[$key]->storeAs($custom_folder_name, $custom_product_name,'public');

        	$storage_path = $custom_folder_name.'/'.$custom_product_name;
        	$url = Storage::url($storage_path);
        	// product ki table ma product ka name, description, category_id aur product_image dalna hoga.
        	DB::insert('insert into purchase_body(purchase_head_id,product_id,user_id, product_purchase_qty, product_purchase_rate, product_sale_price, created_at, updated_at) values (?,?,?,?,?,?,?,?)' , [$purchase_head_id,$this->product_id[$key],$user_id,$this->product_qty[$key],$this->product_purchase_rate[$key],$this->product_sale_price[$key],now(),now()]);
        	DB::table('products')
        	    ->updateOrInsert(
        	        ['id' => $this->product_id[$key], 'user_id' => $user_id],
        	        ['name' => $this->product_name[$key],
                    'description' => $this->product_description[$key],'category_id'=> $this->product_category_id[$key],'img_upload_url' => $url,
                        'deleted_at' => DB::raw('NULL'),
                        'created_at' => DB::raw("IFNULL(created_at, NOW())"),
                        'updated_at' => now() 
                    ]
        	    );
        }

        $this->inputs = [];
   
        $this->resetInputFields();
   
        session()->flash('message', 'Purchase Has Been Inserted Successfully.');
    }
    public function update_sale_price($row_number){
        $this->product_sale_price[$row_number] = $this->product_purchase_rate[$row_number]+$this->product_purchase_rate[$row_number]*0.4;
    }
    public function set_productId($row_number){
        $this->product_id[$row_number] = '';
        $this->product_name[$row_number] = '';
        $this->product_description[$row_number] = '';
        $this->product_category_id[$row_number] = '';
        $this->product_qty[$row_number] = '';
        $this->product_purchase_rate[$row_number] = '';
        $this->product_sale_price[$row_number] = '';
        $this->product_image[$row_number] = null;
        

    }
    public function update_row($row_number){
    	$user_id = Auth::user()->id; 
    	$product_single_record = DB::table('products')->select('name','description','category_id')->where('user_id',$user_id)->where('id',$this->product_id[$row_number])->get();

    	$product_single_record = $product_single_record->toArray();
    	
    	if(empty($product_single_record)){

    		$this->product_name[$row_number] = '';
    		$this->product_description[$row_number] = '';
	    	$this->product_category_id[$row_number] = 0;

    		session()->flash('invalidId', 'Product Not Found for this id');


    		// return redirect()->to('/purchase');
    	}
    	else{
	    	$this->product_name[$row_number] = $product_single_record[0]->name;
	    	$this->product_description[$row_number] = $product_single_record[0]->description;
	    	$this->product_category_id[$row_number] = $product_single_record[0]->category_id;
    	}
    }
    private function resetCategoryInsertionInputFields(){
        $this->category_name= '';
    }
    public function store_category()
    {
        $validatedDate = $this->validate([
            'category_name' => 'required',
        ]);
        Category::create($validatedDate);

        session()->flash('message', 'Category Created Successfully.');

        $this->resetCategoryInsertionInputFields();

        $this->emit('userStore'); // Close model to using to jquery
    }
}