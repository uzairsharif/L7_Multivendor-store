<?php
  
namespace App\Http\Livewire;
  
use Livewire\Component;
// use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Field;
use Illuminate\Http\Request;
use Auth;
  
class SaleReturn extends Component
{
    public $product_id, $product_qty, $product_return_rate;
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
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
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
        // $this->contacts = Contact::all();
        // $this->contacts = DB::table('sale_return_head')->get();
        return view('livewire.sale-return');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    private function resetInputFields(){
        $this->product_id = '';
        $this->product_qty = '';
        $this->product_return_rate = '';

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
                'product_qty.0' => 'required',
                'product_return_rate.0' => 'required',
                'product_id.*' => 'required',
                'product_qty.*' => 'required',
                'product_return_rate.*' => 'required',
            ],
            [
                'product_id.0.required' => 'Product ID field is required',
                'product_qty.0.required' => 'Product Quantity field is required',
                'product_return_rate.0.required' => 'Product reutrn Rate field is required',
                'product_id.*.required' => 'Product ID field is required',
                'product_qty.*.required' => 'Product Quantity field is required',
                'product_return_rate.*.required' => 'Product Return Rate field is required',
            ]
        );
   		$user_id = Auth::user()->id;
        DB::insert('insert into sale_return_head (created_at, updated_at) values (?, ?)', [ now(), now()]);
        $sale_return_head_id = DB::getPdo()->lastInsertId();
        foreach ($this->product_id as $key => $value) {

            // Contact::create(['name' => $this->name[$key], 'phone' => $this->phone[$key]]);
        	DB::insert('insert into sale_return_body(sale_return_head_id,product_id, user_id, product_qty, product_sale_rate, created_at, updated_at) values (?,?,?,?,?,?,?)' , [$sale_return_head_id, $this->product_id[$key],$user_id,$this->product_qty[$key],$this->product_return_rate[$key],now(),now()]);
        }
  
        $this->inputs = [];
   
        $this->resetInputFields();
   
        session()->flash('message', 'Return Has Been Created Successfully.');
    }
}