<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;
use Auth;

class Calculations extends Component
{
    public $calProducts = [];
    public $find_product_id = '';
    protected $products;
    public $profit = [];
    public $sold_price;
    public $input_percentage = [];
    public $Rate = [];
    public $percentage_fraction;
    public $total_Profit;
    public $total_Percentage;
    public $total_purchase_price;
    public $total_sale_price;
    public $Product_qty = [];
    public $Total_Price = [];
    public $purchase_rate_mul_qty =[];
    public $Grand_Total;
    public $Grand_purchased_price_mul_qty;
    public $alert;
    public $existed_product_id;
    public $product_not_exists;
    public $stock_end;
    public $user_id;
    public $my = [];
    
    protected $listeners = ['Set_Product_Id_Emit' => 'Set_Product_Id', 'product_name_validation_Emit' => 'Add_product_name_validation_error_inErrorbag', 'call_addProduct'=>'addProduct'];
    

    //protected rules is used here for laravel livewire validation....
    // validate() is done below in the set_quantity method which gives
    // $message to show message in our calculations.blade.php livewire view.
    protected $rules = [
        // 'name' => 'required|min:6',
        'Product_qty.*' => 'required|numeric',
        'Rate.*' => 'required|numeric',
        'Total_Price.*'=> 'required|numeric',
        'input_percentage.*' => 'required|numeric|min:25',
        // 'input_percentage' => ['required','numeric', 'min:25', 'regex:/^\d+(\.\d{1,2})?$/'],
        'find_product_id' => 'required|numeric',
        
        'total_Percentage' => 'required|numeric',
        'Grand_Total' => 'required|numeric'
    ];
    
    protected $validationAttributes = [
        'find_product_id' => 'Product id',
        'Rate.*' => 'Rate',
        
        'Product_qty.*'=> 'Quantity',
        'input_percentage.*' => 'Percentage'
    ];
    protected $messages = [
        'Product_qty.*.required' => 'Quantity can not be empty. Insert Quantity then proceed'
    ];
    public function __construct(){
        $this->user_id = Auth::user()->id;
    }
    public function render()
    {    
        return view('livewire.calculations');
    }
    public function mount()
    {
        
        $this->total_Profit = 0;
        $this->total_Percentage = 0;
        $this->Grand_Total = 0;
        
        // $this->input_percentage [] = 20;  // ye 0 index pr 20 rakh rha hai.
    }
    public function Add_product_name_validation_error_inErrorbag($product_name_error_message){
        $this->addError('query', $product_name_error_message);
    }
    public function Set_Product_Id($product_id_to_be_searched){

        $this->find_product_id = $product_id_to_be_searched;
        $this->addProduct();
    }
    public function alertDismiss(){
        $this->alert = false;
        $this->product_not_exists = false;
        // dd($this->alert);
    }
    // below method was called from calculations.blade.php on button click and emited to contactserachBar.php
    // file which is a child component.
    // public function product_name_field_validation(){
    //     $this->emit('product_name_field_validation');   
    // }
    public function addProduct(){ 
        
        $this->product_not_exists = false;
        $this->alert = false;
        $this->validate();
        $this->alert = false;
        $Product_exists = false;
        $this->existed_product_id = null;
        $this->stock_end = false;
        $this->product_not_exists = false;

        foreach($this->calProducts as $single_products_data){
            if($single_products_data['id'] == $this->find_product_id){
                $this->existed_product_id = $this->find_product_id;
                $this->alert = true;
                $Product_exists = true;
            }
        }

        if(!$Product_exists){
            // $this->products = Product::where(['id'=>$this->find_product_id,'user_id'=>$this->user_id])->first();


        //     $this->products = DB::table('purchase_body')
        // ->join('products', function ($join) {
        //     $join->on('purchase_body.product_id', '=', 'products.id')->on('purchase_body.user_id','=','products.user_id');
        // })
        // ->where('purchase_body.product_id' ,$this->find_product_id)
        // ->where('purchase_body.user_id' , $this->user_id)
        // ->select('purchase_body.product_id as id','products.name','purchase_body.product_purchase_rate as purchased_price','products.img_upload_url')
        // ->addSelect(DB::raw('sum(product_purchase_qty) as stock'))
        // ->groupby('purchase_body.product_id')
        // ->first();

            // ->addSelect(DB::raw('sum(product_purchase_qty) as stock'))
        $this->products = DB::select ('select my_union.product_id as id,products.name,products.img_upload_url, my_union.user_id,sum(my_union.product_purchase_qty) as stock , (select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price, (select product_sale_price from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price from(select product_id,user_id,product_purchase_qty,product_purchase_rate from purchase_body where deleted_at IS NULL union select product_id, user_id, -product_qty, product_sale_rate from order_item where deleted_at IS NULL)my_union left join products on my_union.product_id = products.id and my_union.user_id = products.user_id where my_union.product_id ='.$this->find_product_id.' and my_union.user_id ='.$this->user_id.' group by product_id,user_id having stock != 0 or stock < 0');
        if(!empty($this->products))
        $this->products = (object) $this->products[0];
        // dd($this->products);
       
        // $this->products = DB::table('purchase_body')->where('purchase_body.product_id',$this->find_product_id)->where('purchase_body.user_id',$this->user_id)->join('products',function($join){$join->on('purchase_body.product_id', '=', 'products.id')->on('purchase_body.user_id','=','products.user_id');})->select('purchase_body.product_id as id','products.name','purchase_body.product_purchase_rate as purchased_price','products.img_upload_url')->addSelect(DB::raw('sum(product_purchase_qty) as stock'))->addSelect(DB::raw('purchase_body.product_purchase_rate + purchase_body.product_purchase_rate*0.4 as Sale_price'))->groupby('purchase_body.product_id','products.name','purchase_body.product_purchase_rate','products.img_upload_url')->
        //     first();

        // dd($this->products);
            // DB::table('purchase_body')->where('purchase_body.product_id','1')->where('purchase_body.user_id','4')->join('products',function($join){$join->on('purchase_body.product_id', '=', 'products.id')->on('purchase_body.user_id','=','products.user_id');})->select('purchase_body.product_id as id','products.name','purchase_body.product_purchase_rate as purchased_price','products.img_upload_url')->addSelect(DB::raw('sum(product_purchase_qty) as stock'))->addSelect(DB::raw('purchase_body.product_purchase_rate + purchase_body.product_purchase_rate*0.4 as Sale_price'))->groupby('product_id','products.name','purchase_body.product_purchase_rate','products.img_upload_url')->first();


            // dd($this->products);

            if($this->products == null){ 
                $this->products = Product::where(['id'=>$this->find_product_id,'user_id'=>$this->user_id,'deleted_at' => null])->first();
                if($this->products == null)
                    $this->product_not_exists = true;
                else
                    $this->stock_end = true;
                }
            else{
                $this->calProducts [] = ['id'=>$this->products->id,
                                    'name'=>$this->products->name,
                                    'purchase_price'=>$this->products->purchased_price,
                                    'img_upload_url'=>$this->products->img_upload_url,
                                    'regular_price'=>$this->products->purchased_price,
                                    'sale_price'=>$this->products->Sale_price,
                                    'stock' => $this->products->stock
                                ];

                $input_percentage_index = count($this->calProducts) - 1;
                // $this->input_percentage[$input_percentage_index] = 40;
                $my_profit = $this->products->Sale_price-$this->products->purchased_price;
                $profit_divided_by_purchase_price = $my_profit/$this->products->purchased_price;
                $this->input_percentage[$input_percentage_index] = $profit_divided_by_purchase_price * 100;
                // dd($this->input_percentage[$input_percentage_index]);

                if($this->calProducts[$input_percentage_index]['stock']< 1)
                $this->Product_qty[$input_percentage_index] = 0;
                else
                $this->Product_qty[$input_percentage_index] = 1;

                $this->percentage_fraction[$input_percentage_index] = $this->input_percentage[$input_percentage_index]/100;
                
                $this->profit[$input_percentage_index] = $this->products->purchased_price*$this->percentage_fraction[$input_percentage_index];
                
                $this->Rate[$input_percentage_index] = $this->products->Sale_price;

                $this->Total_Price[$input_percentage_index] = $this->Product_qty[$input_percentage_index]*$this->Rate[$input_percentage_index];                                
                $this->purchase_rate_mul_qty[$input_percentage_index] = $this->Product_qty[$input_percentage_index]*$this->products->purchased_price;
                

                $this->total_Profit += $this->profit[$input_percentage_index];
                
                $this->total_purchase_price +=$this->products->purchased_price;
                $this->total_sale_price += $this->Rate[$input_percentage_index];
                
                // $this->total_Percentage = $this->total_Profit/$this->total_purchase_price*100;
                //...........................................................
                // $this->total_Percentage = 0;
                // foreach($this->input_percentage as $index=>$single_percentage)
                // {
                //     $this->total_Percentage += $single_percentage;
                //     if ($index === array_key_last($this->input_percentage)){
                //         $total_percentage_count = $index + 1;
                //         $this->total_Percentage = $this->total_Percentage/$total_percentage_count;
                //     }

                // }
                //......................................
                // we have to change above code to make total percentage work properly......
                $this->Grand_Total = 0;

                foreach($this->Total_Price as $index => $single_price){
                    $this->Grand_Total += $single_price;

                }
                $this->Grand_purchased_price_mul_qty = 0;
                foreach($this->purchase_rate_mul_qty as $index=>$single_purchase_rate_mul_qty){
                    $this->Grand_purchased_price_mul_qty += $single_purchase_rate_mul_qty;
                 
                }

                $actual_price_without_profit = $this->Grand_Total-$this->total_Profit;
                $this->total_Percentage = $this->total_Profit/$actual_price_without_profit*100;
                
                // $this->find_product_id = '';
                $this->product_not_exists = false;
                $this->reset('find_product_id');

                return view('livewire.calculations', ['products'=>$this->calProducts]);
                
                $this->emit('rerenderSidebar');    
            }    
        }
    }

    public function updateProfit($update_index){
        $this->validate([
            'input_percentage.*' => 'required|numeric|min:25',
        ]);
        // $this->validate();
            
        $this->percentage_fraction[$update_index] = $this->input_percentage[$update_index]/100;

        $this->Rate[$update_index] = $this->calProducts[$update_index]['purchase_price']+$this->calProducts[$update_index]['purchase_price']*$this->percentage_fraction[$update_index];
        
        // $this->Rate[$update_index] = $this->products->purchased_price + $this->profit[$update_index];
        $this->Total_Price[$update_index] = $this->Product_qty[$update_index]*$this->Rate[$update_index];
        $this->purchase_rate_mul_qty[$update_index] = $this->Product_qty[$update_index]*$this->calProducts[$update_index]['purchase_price'];
        $this->profit[$update_index] = $this->calProducts[$update_index]['purchase_price']*$this->percentage_fraction[$update_index]*$this->Product_qty[$update_index];

        // $this->profit[$update_index] = $this->Total_Price[$update_index]-$this->Product_qty[$update_index]*$this->products->purchased_price;

        // $this->profit[$update_index] = $this->Total_Price[$update_index]-$this->Product_qty[$update_index]*$this->products->purchased_price;
        $this->total_Profit = 0;
        foreach($this->profit as $singleprofit)
        {
            $this->total_Profit += $singleprofit;
        }
        //$this->products->purchased_price jo hai shayad us ki wajah sa masla aa rha hai.
        // $this->sold_price = $this->products->purchased_price+$this->profit;
        //......................................
        // $this->total_Percentage = 0;
        // foreach($this->input_percentage as $index=>$single_percentage)
        // {
        //     $this->total_Percentage += $single_percentage;
        //     if ($index === array_key_last($this->input_percentage)){
        //         $total_percentage_count = $index + 1;
        //         $this->total_Percentage = $this->total_Percentage/$total_percentage_count;
        //     }

        // }
        //..........................
        $this->Grand_Total = 0;
        foreach($this->Total_Price as $index=>$single_price){
                $this->Grand_Total += $single_price;   
        }
        // foreach($this->purchase_rate_mul_qty as $index=>$single_purchase_rate_mul_qty){
        //         $this->Grand_purchased_price_mul_qty += $single_purchase_rate_mul_qty;   
        // }

        $actual_price_without_profit = $this->Grand_Total-$this->total_Profit;
        $this->total_Percentage = $this->total_Profit/$actual_price_without_profit*100;

    }

    public function updateRate($update_index){
        // $this->validate();
        
        $item_minimum_rate =[];
        $item_minimum_rate[$update_index] = number_format(0.25,2)*$this->calProducts[$update_index]['purchase_price']+$this->calProducts[$update_index]['purchase_price'];
        
        $this->validate(
            [   
            "Rate.$update_index" => "required|numeric|min:$item_minimum_rate[$update_index]",
            'input_percentage.*' => 'required|numeric|min:25',
            ],
            //ye $message wala kam kray
            ['Rate.*.min' => "Rate shouldn't be less than $item_minimum_rate[$update_index] as you are earning less than 25% profit"],
            //jaisay ooper kia na validationAttribute to ye custom validationAttribute hai.
            ["Rate.$update_index" => "Rate"]
            //
        );
    
        $this->Total_Price[$update_index] = $this->Product_qty[$update_index]*$this->Rate[$update_index];

        
        $this->profit[$update_index] = $this->Total_Price[$update_index]-$this->Product_qty[$update_index]*$this->calProducts[$update_index]['purchase_price'];
        $single_qty_profit = $this->Rate[$update_index]- $this->calProducts[$update_index]['purchase_price'];
        $this->input_percentage[$update_index] = $single_qty_profit / $this->calProducts[$update_index]['purchase_price']*100;
        // $this->validate([   
        //     'Rate.*' => 'required|numeric',
        //     'input_percentage.*' => 'required|numeric|min:25',
        // ]);
        $this->total_Profit = 0;
        foreach($this->profit as $singleprofit)
        {
            $this->total_Profit += $singleprofit;
            $this->total_Profit = $this->total_Profit;
        }   
        // dd($this->input_percentage);
        // //.........................................
        //     $this->total_Percentage = 0;
        // foreach($this->input_percentage as $index=>$single_percentage)
        // {
        //     $this->total_Percentage += $single_percentage;
        //     if ($index === array_key_last($this->input_percentage)){
        //         $total_percentage_count = $index + 1;
        //         $this->total_Percentage = $this->total_Percentage/$total_percentage_count;
        //     }

        // }
        //............................................
        $this->Grand_Total = 0;
        foreach($this->Total_Price as $index=>$single_price){
            $this->Grand_Total += $single_price;    
            $this->Grand_Total = $this->Grand_Total;
        }
        $actual_price_without_profit = $this->Grand_Total-$this->total_Profit;
        $this->total_Percentage = $this->total_Profit/$actual_price_without_profit*100;
        $this->total_Percentage = $this->total_Percentage;
    }
    public function set_quantity($update_index){
        // if($this->Product_qty[$update_index] >=)
        $this->validate([
            'Product_qty.*' => 'required|numeric',
            // 'Rate.*' => 'required|numeric',
            // 'Total_Price.*'=> 'required|numeric',
            // 'input_percentage.*' => 'required|numeric|min:25',
            // 'total_Percentage' => 'required|numeric',
        ]);
        // $this->validate();

        $product_stock = $this->calProducts[$update_index]['stock'];

        $product_quantity = $this->Product_qty[$update_index];
        // dd($product_quantity);
        if($product_quantity > $product_stock){
            $this->Product_qty[$update_index] = $product_stock;
            session()->flash('lowStock', 'Quantity is greater than available stock. Only ' . $product_stock. ' products are available');
            $this->purchase_rate_mul_qty[$update_index] = $this->Product_qty[$update_index]*$this->calProducts[$update_index]['purchase_price'];
            $this->Total_Price[$update_index] = $this->Product_qty[$update_index]*$this->Rate[$update_index];
            $this->profit[$update_index] = $this->Total_Price[$update_index]-$this->Product_qty[$update_index]*$this->calProducts[$update_index]['purchase_price'];
            $this->total_Profit = 0;
            foreach($this->profit as $singleprofit)
            {
                $this->total_Profit += $singleprofit;
                $this->total_Profit = $this->total_Profit;
            }
           
            $this->Grand_Total = 0;
            foreach($this->Total_Price as $index=>$single_price){
                $this->Grand_Total += $single_price;
                $this->Grand_Total = $this->Grand_Total;
            }
            $this->Grand_purchased_price_mul_qty = 0;
             foreach($this->purchase_rate_mul_qty as $index=>$single_purchase_rate_mul_qty){
               $this->Grand_purchased_price_mul_qty += $single_purchase_rate_mul_qty;
            }
            $actual_price_without_profit = $this->Grand_Total-$this->total_Profit;
            $this->total_Percentage = $this->total_Profit/$actual_price_without_profit*100;
            $this->total_Percentage = $this->total_Percentage;
            
        }
        else{
            $this->purchase_rate_mul_qty[$update_index] = $this->Product_qty[$update_index]*$this->calProducts[$update_index]['purchase_price'];
            $this->Total_Price[$update_index] = $this->Product_qty[$update_index]*$this->Rate[$update_index];
            
            
            $this->profit[$update_index] = $this->Total_Price[$update_index]-$this->Product_qty[$update_index]*$this->calProducts[$update_index]['purchase_price'];
            $this->total_Profit = 0;
            foreach($this->profit as $singleprofit)
            {
                $this->total_Profit += $singleprofit;
                $this->total_Profit = $this->total_Profit;
            }
            //............................
            //  $this->total_Percentage = 0;
            // foreach($this->input_percentage as $index=>$single_percentage)
            // {
            //     $this->total_Percentage += $single_percentage;
            //     if ($index === array_key_last($this->input_percentage)){
            //         // dd($index);
            //         // dd(array_key_last($this->input_percentage));

            //         $total_percentage_count = $index + 1;
            //         $this->total_Percentage = $this->total_Percentage/$total_percentage_count;
            //     }

            // }
            //...........................
            $this->Grand_Total = 0;
            foreach($this->Total_Price as $index=>$single_price){
                $this->Grand_Total += $single_price;
                $this->Grand_Total = $this->Grand_Total;
            }
            $this->Grand_purchased_price_mul_qty = 0;
             foreach($this->purchase_rate_mul_qty as $index=>$single_purchase_rate_mul_qty){
               $this->Grand_purchased_price_mul_qty += $single_purchase_rate_mul_qty;
            }
            $actual_price_without_profit = $this->Grand_Total-$this->total_Profit;
            $this->total_Percentage = $this->total_Profit/$actual_price_without_profit*100;
            $this->total_Percentage = $this->total_Percentage;
        }    
    }

    public function discountGranttotal(){
        $this->validate([   
            'Grand_Total' => 'required|numeric',
        ]);

       $this->total_Profit = $this->Grand_Total - $this->Grand_purchased_price_mul_qty;
       $actual_price_without_profit = $this->Grand_Total-$this->total_Profit;
       $this->total_Percentage = $this->total_Profit/$actual_price_without_profit*100;
       $count = count($this->input_percentage);
        // dd($count);
        $start_index = 0;
        $value = $this->total_Percentage;
        $this->input_percentage = array_fill ( $start_index , $count , $value );
        for($i=0;$i<$count; $i++){
            $this->updateProfit($i);
        }
    }
    public function discountTotalPercentage(){

        $this->validate([   
            'total_Percentage' => 'required|numeric',
        ]);
        $this->total_Profit = $this->Grand_purchased_price_mul_qty*$this->total_Percentage/100;
        $this->Grand_Total = $this->Grand_purchased_price_mul_qty + $this->total_Profit;
        $count = count($this->input_percentage);
        // dd($count);
        $start_index = 0;
        $value = $this->total_Percentage;
        $this->input_percentage = array_fill ( $start_index , $count , $value );
        for($i=0;$i<$count; $i++){
            $this->updateProfit($i);
        }
        
        // dd($this->input_percentage);
    }
    
}
