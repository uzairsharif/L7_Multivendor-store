<?php
// 


namespace App\Http\Controllers;

use App\Order;
use App\Customer;
//use App\Cart;   // agar isko use na krain to koi masla ata hai?
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrder;
// use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Session;
use Config;
use Auth;
use PDF;


// use LaravelDaily\Invoices\Invoice;
// use LaravelDaily\Invoices\Classes\Buyer;
// use LaravelDaily\Invoices\Classes\InvoiceItem;
//advanced usage
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class OrderController extends Controller
{
    public function getLoggedInUserId(){
      return Auth::user()->id;
    }
    //  ye ooper wala kam kia ma na k user_id controller ma har method ma use krni thi. lakin agar constructor k andar code likhtay thay to phir constructor ma Auth::check() kam nhi krta tha to ma na aesay kr dia. ooper method bna dia.
    // public $user_id;
    // public function __construct(){
    //   if(Auth::check()){
    //       $this->middleware(function ($request, $next) {
    //           $this->user_id = Auth::user()->id;
    //           return $next($request);
    //       });   
    //   } 
    // }

   
    // public function __construct(){
    //       $this->middleware(function ($request, $next) {
    //         $this->user_id = Auth::user()->id;
    //         return $next($request);
    //     });
    // }
    public function checkout(){
      
        if(!Session::has('cart') || empty(Session::get('cart')->getContents())){
          return redirect('product_card')->with('message', 'No Products in the cart');
            // return view('cart')->with('Message' , 'No Products in the cart');
          }
          $cart = Session::get('cart');
          return view('product.checkout', compact('cart'));
    }
    public function jazzcash_checkout(StoreOrder $request){
      $order = '';
      $checkout = '';
      $cart= [];// matlab cart ma agar koi data nhi to error na day aur phir isi liay $cart = [] kr dia.
      if(Session::has('cart')){
        $cart = Session::get('cart');
      }

      if($request->shipping_address){
        $customer = [
        "billing_firstName" => $request->billing_firstName,
        "billing_lastName" => $request->billing_lastName,
        "username" =>$request->username,
        "email" => $request->email,
        "billing_address1" =>$request->billing_address1,
        "billing_address2" => $request->billing_address2,
        "billing_country"=> $request->billing_country,
        "billing_state" => $request->billing_state,
        "billing_zip" => $request->billing_zip,
      
        "shipping_firstName" => $request->shipping_firstName,
        "shipping_lastName" => $request->shipping_lastName,
        "shipping_address1" => $request->shipping_address,
        "shipping_address2" => $request->shipping_address2,
        "shipping_country" => $request->shipping_country,
        "shipping_state" => $request->shipping_state,
        "shipping_zip" => $request->shipping_zip,
      ];
      }else{
        $customer = [
        "billing_firstName" => $request->billing_firstName,
        "billing_lastName" => $request->billing_lastName,
        "username"=> $request->username,
        "email"=> $request->email,
        "billing_address1" => $request->billing_address1,
        "billing_address2" => $request->billing_address2,
        "billing_country" => $request->billing_country,
        "billing_state" => $request->billing_state,
        "billing_zip" => $request->billing_zip,
        ];
      }
      DB::beginTransaction();
      $checkout = Customer::create($customer);
      ///////////////////////do checkout code............
          $data = $request->input();
          //print_r($data);

          
          // $product_id = $data['product_id'];
          // $product = DB::select('select * from product where product_id='.$product_id);
          
          //1.
          //get formatted price. remove period(.) from the price
          // $temp_amount    = $product[0]->price*100;
          // $amount_array   = explode('.', $temp_amount);
          // $pp_Amount      = $amount_array[0];
          $pp_Amount = $data['cart_total'] ;
          //2.
          //get the current date and time
          //be careful set TimeZone in config/app.php
          $DateTime       = new \DateTime();

          $pp_TxnDateTime = $DateTime->format('YmdHis');
          //3.
          //to make expiry date and time add one hour to current date and time
          $ExpiryDateTime = $DateTime;

          $ExpiryDateTime->modify('+' . 1 . ' hours');
          $pp_TxnExpiryDateTime = $ExpiryDateTime->format('YmdHis');
          
          //4.
          //make unique transaction id using current date
          $pp_TxnRefNo = 'T'.$pp_TxnDateTime;
          

          $post_data =  array(
              "pp_Version"            => Config::get('constants.jazzcash.VERSION'),
              "pp_TxnType"            => "MWALLET",
              "pp_Language"           => Config::get('constants.jazzcash.LANGUAGE'),
              "pp_MerchantID"         => Config::get('constants.jazzcash.MERCHANT_ID'),
              "pp_SubMerchantID"      => "",
              "pp_Password"           => Config::get('constants.jazzcash.PASSWORD'),
              "pp_BankID"             => "TBANK",
              "pp_ProductID"          => "RETL",
              "pp_TxnRefNo"           => $pp_TxnRefNo,
              "pp_Amount"             => $pp_Amount,
              "pp_TxnCurrency"        => Config::get('constants.jazzcash.CURRENCY_CODE'),
              "pp_TxnDateTime"        => $pp_TxnDateTime,
              "pp_BillReference"      => "billRef",
              "pp_Description"        => "Description of transaction",
              "pp_TxnExpiryDateTime"  => $pp_TxnExpiryDateTime,
              "pp_ReturnURL"          => Config::get('constants.jazzcash.RETURN_URL'),
              "pp_SecureHash"         => "",
              "ppmpf_1"               => "1",
              "ppmpf_2"               => "2",
              "ppmpf_3"               => "3",
              "ppmpf_4"               => "4",
              "ppmpf_5"               => "5",
          );
          
          $pp_SecureHash = $this->get_SecureHash($post_data);
          
          $post_data['pp_SecureHash'] = $pp_SecureHash;
          
          // $order_data = [
          //     'TxnRefNo'    => $post_data['pp_TxnRefNo'],
          //     'amount'      => $data['cart_total'],
          //     'description' => $post_data['pp_Description'],
          //     'status'      => 'pending'
          //   ];
          $order_id = DB::table('orders')->max('id');

          $next_order_id = $order_id +1;
          
      $order_data = [
        // 'id' => $post_data['pp_TxnRefNo'],
        'id' => $next_order_id,
        'profit' => '500',
        'grand_total' => $data['cart_total'],
        'user_id' => $checkout->id,
        'status' => 'pending',
        'payment_id' => 0,
      ];
      $order = Order::create($order_data);
      // DB::table('order')->insert($values);
      Session::put('post_data',$post_data);
      foreach($cart->getContents() as $id=>$product_with_ids_array){
        foreach($product_with_ids_array as $single_users_product_data){
          // dd($single_users_product_data);
            $products = [
              //is ko uzair na order_item ma dala...
              // payment_id ko order ki table ma dala jb payment
              // ho jae gi aur status ko bhi udhar hi orders ki table
              // ma dala.....
              'order_id'=> $next_order_id,
              // 'user_id' => $checkout->id,
              'product_id' => $single_users_product_data['product']->id, 

              'product_qty'=>$single_users_product_data['qty'],
              // 'status' => 'Pending', 
              'product_sale_rate' => $single_users_product_data['price'],
              // 'payment_id' => 0,
            ];
            $order_item = DB::table('order_item')->insert($products);
            // $order = Order::create($products);
        }
      }
        if($checkout && $order && $order_item){
          DB::commit();
          
              return view('orders.do_checkout_v');
          
          // return view('products.payments');
        } 
        else{
          DB::rollback();
          return redirect('checkout')->with('message', 'Invalid Activity!');
        }
    }
    public function stripe_checkout(StoreOrder $request){

      $order = '';
      $checkout = '';
      $cart= [];// matlab cart ma agar koi data nhi to error na day aur phir isi liay $cart = [] kr dia.
      if(Session::has('cart')){
        $cart = Session::get('cart');
         
      }
  
      if($request->shipping_address){
        $customer = [
        "billing_firstName" => $request->billing_firstName,
        "billing_lastName" => $request->billing_lastName,
        "username" =>$request->username,
        "email" => $request->email,
        "billing_address1" =>$request->billing_address1,
        "billing_address2" => $request->billing_address2,
        "billing_country"=> $request->billing_country,
        "billing_state" => $request->billing_state,
        "billing_zip" => $request->billing_zip,
      
        "shipping_firstName" => $request->shipping_firstName,
        "shipping_lastName" => $request->shipping_lastName,
        "shipping_address1" => $request->shipping_address,
        "shipping_address2" => $request->shipping_address2,
        "shipping_country" => $request->shipping_country,
        "shipping_state" => $request->shipping_state,
        "shipping_zip" => $request->shipping_zip,
      ];
      }else{
        $customer = [
        "billing_firstName" => $request->billing_firstName,
        "billing_lastName" => $request->billing_lastName,
        "username"=> $request->username,
        "email"=> $request->email,
        "billing_address1" => $request->billing_address1,
        "billing_address2" => $request->billing_address2,
        "billing_country" => $request->billing_country,
        "billing_state" => $request->billing_state,
        "billing_zip" => $request->billing_zip,
        ];
      }
      DB::beginTransaction();

      $customer = Customer::create($customer); 

      ///////////////////////do checkout code............
          $data = $request->input();
           
            
          $order_id = DB::table('orders')->max('id');

          $next_order_id = $order_id +1;
           // below is the math formula for sale price
  // Selling Price = Cost price + (Profit Percentage/ 100) * Cost Price and made formula to calculate the cost price.  
          $Profit_Percentage = 40;
          $Selling_Price = $cart->getTotalPrice();
            
          $Cost_Price =  (100/(100+$Profit_Percentage))*$Selling_Price;
           
          $transaction_total_profit = $Selling_Price - $Cost_Price;
           
       
          
      $order_data = [
        // 'id' => $post_data['pp_TxnRefNo'],
        'id' => $next_order_id,
        // 'profit' => $transaction_total_profit,
        'grand_total' => $data['cart_total'],
        // 'user_id' => $customer->id,
        'status' => 'pending',
        'payment_id' => 0,
        'customer_id' => $customer->id,
      ];
      $order = Order::create($order_data); 
      foreach($cart->getContents() as $id=>$product_with_ids_array){
        // dd($cart);
        foreach($product_with_ids_array as $single_users_product_data){
          // dd($single_users_product_data);

            $single_order_item = [
               
              'order_id'=> $next_order_id,
              'product_id' => $single_users_product_data['product']->id, 
              'user_id' => $single_users_product_data['product']->user_id,

              'product_qty'=>$single_users_product_data['qty'],
              // 'status' => 'Pending', 
              'product_sale_rate' => $single_users_product_data['product']->Sale_price,
              'created_at' => now(),
              'updated_at' => now(),
              // 'payment_id' => 0,
            ];        
            // dd($single_order_item);
            $order_item = DB::table('order_item')->insert($single_order_item);

            $user_id_product_owner = $single_users_product_data['product']->user_id;

            $product_id_of_sold_item = $single_users_product_data['product']->id;

            $sold_product_qty = $single_users_product_data['qty'];

            // $decrement_sold_products = DB::table('products')->where('user_id', $user_id_product_owner)->where('id',$product_id_of_sold_item)->decrement('stock', $sold_product_qty);
            // $order = Order::create($products);
        }
      }
        if($customer && $order && $order_item){

          DB::commit();
          $pay_price = $data['cart_total'];
          
          $request->session()->put('paid_order_id', $next_order_id);
          // if ($request->session()->has('paid_order_id')) {
              
          // } 
              return view('stripe',compact('pay_price'));
              

              // return view('orders.do_checkout_v');
          
          // return view('products.payments');
        } 
        else{
          DB::rollback();
          return redirect('checkout')->with('message', 'Invalid Activity!');
        }
    }
    public function DoCheckout(Request $request)
        {
            dd($request);
            $data = $request->input();
            //print_r($data);

            
            $product_id = $data['product_id'];
            $product = DB::select('select * from product where product_id='.$product_id);
            
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            //1.
            //get formatted price. remove period(.) from the price
            $temp_amount    = $product[0]->price*100;
            $amount_array   = explode('.', $temp_amount);
            $pp_Amount      = $amount_array[0];
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            //2.
            //get the current date and time
            //be careful set TimeZone in config/app.php
            $DateTime       = new \DateTime();
            $pp_TxnDateTime = $DateTime->format('YmdHis');
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            //3.
            //to make expiry date and time add one hour to current date and time
            $ExpiryDateTime = $DateTime;
            $ExpiryDateTime->modify('+' . 1 . ' hours');
            $pp_TxnExpiryDateTime = $ExpiryDateTime->format('YmdHis');
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            //4.
            //make unique transaction id using current date
            $pp_TxnRefNo = 'T'.$pp_TxnDateTime;
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            
            //--------------------------------------------------------------------------------
            //$post_data

            $post_data =  array(
                "pp_Version"            => Config::get('constants.jazzcash.VERSION'),
                "pp_TxnType"            => "MWALLET",
                "pp_Language"           => Config::get('constants.jazzcash.LANGUAGE'),
                "pp_MerchantID"         => Config::get('constants.jazzcash.MERCHANT_ID'),
                "pp_SubMerchantID"      => "",
                "pp_Password"           => Config::get('constants.jazzcash.PASSWORD'),
                "pp_BankID"             => "TBANK",
                "pp_ProductID"          => "RETL",
                "pp_TxnRefNo"           => $pp_TxnRefNo,
                "pp_Amount"             => $pp_Amount,
                "pp_TxnCurrency"        => Config::get('constants.jazzcash.CURRENCY_CODE'),
                "pp_TxnDateTime"        => $pp_TxnDateTime,
                "pp_BillReference"      => "billRef",
                "pp_Description"        => "Description of transaction",
                "pp_TxnExpiryDateTime"  => $pp_TxnExpiryDateTime,
                "pp_ReturnURL"          => Config::get('constants.jazzcash.RETURN_URL'),
                "pp_SecureHash"         => "",
                "ppmpf_1"               => "1",
                "ppmpf_2"               => "2",
                "ppmpf_3"               => "3",
                "ppmpf_4"               => "4",
                "ppmpf_5"               => "5",
            );
            
            $pp_SecureHash = $this->get_SecureHash($post_data);
            
            $post_data['pp_SecureHash'] = $pp_SecureHash;


            
            $values = array(
                'TxnRefNo'    => $post_data['pp_TxnRefNo'],
                'amount'      => $product[0]->price,
                'description' => $post_data['pp_Description'],
                'status'      => 'pending'
            );
            DB::table('order')->insert($values);
            
            
            Session::put('post_data',$post_data);
            echo '<pre>';
            print_r($post_data);
            echo '</pre>';
            
            return view('do_checkout_v');
            
            
        }
        
        //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
        private function get_SecureHash($data_array)
        {
            ksort($data_array);
            
            $str = '';
            foreach($data_array as $key => $value){
                if(!empty($value)){
                    $str = $str . '&' . $value;
                }
            }
            
            $str = Config::get('constants.jazzcash.INTEGERITY_SALT').$str;
            
            $pp_SecureHash = hash_hmac('sha256', $str, Config::get('constants.jazzcash.INTEGERITY_SALT'));
            
            //echo '<pre>';
            //print_r($data_array);
            //echo '</pre>';
            
            return $pp_SecureHash;
        }
        
        
        
        //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
        public function paymentStatus(Request $request)
        {
            $response = $request->input();
            echo '<pre>';
            print_r($response);
            echo '</pre>';
            
            if($response['pp_ResponseCode'] == '000')
            {
                $response['pp_ResponseMessage'] = 'Your Payment has been Successful';
                $values = array('status' => 'completed');
                DB::table('order')
                    ->where('TxnRefNo',$response['pp_TxnRefNo'])
                    ->update(['status' => 'completed']);
            }
            
            return view('payment-status',['response'=>$response]);
        }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
      $user_id = $this->getLoggedInUserId();
      $orders = DB::table('order_item')->join('orders','order_item.order_id','=','orders.id')->join('customers', 'customers.id', '=', 'orders.customer_id')->select('order_item.order_id as id','orders.customer_id','customers.username as buyerName',DB::raw("sum(order_item.product_sale_rate*order_item.product_qty) as grand_total"),'order_item.created_at','order_item.updated_at')->groupby('order_item.order_id','order_item.user_id')->having('order_item.user_id', $user_id)->paginate(10);

    
        // $orders = DB::table('order_item')
        // ->join('users', 'order_item.user_id', '=', 'users.id')
        // ->where('order_item.user_id',$this->user_id)
        // ->select('order_item.*','users.name as buyerName')

        // ->paginate(10);
        // ->get();  when we use paginate then don't use get();

        // $orders = DB::table('orders')->get();
        // dd($orders);
        return view('orders.orders' , compact('orders'));
    }

    public function profit(Request $request)
    { 
        $user_id = $this->getLoggedInUserId();
        $profit_date_from = $request->input('date_from');
        $profit_date_to = $request->input('date_to');

        if($profit_date_from and $profit_date_to)
        {
          $profit =  DB::table('order_item')->select(DB::raw('sum(products.purchased_price*order_item.product_qty) as cost,sum(order_item.product_sale_rate) as sale'),'order_item.user_id as OrderItemUserID',DB::raw("sum(order_item.product_sale_rate - products.purchased_price*order_item.product_qty) as profit"))->join('products', function ($join){ $join->on('order_item.product_id', '=', 'products.id')->on('order_item.user_id', '=', 'products.user_id');})->where('order_item.user_id',$user_id)->groupby('order_item.user_id')->get();
          $profit = $profit[0]->profit;
                
            return view('orders.profit' , compact('profit'));
        }

        else
            return view('orders.profit');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $user_id = $this->getLoggedInUserId();
        if($request->print){
          $Grand_Total = $request->input('GrandTotal');
          // $Grand_Total = array('Grand_Total' => $Grand_Total);
          // $Total_Profit = $request->input('TotalProfit');
          // $Total_Profit = array('Total_Profit' => $Total_Profit);

          $previous_order_id = DB::table('orders')->max('id');

          $Invoice_order_id = $previous_order_id + 1; 
          // $Invoice_order_id = array('Invoice_order_id' => $Invoice_order_id);
          
          $Invoice_created_at = now()->format('m/d/Y');
          // $Invoice_created_at = array('Invoice_created_at' => $Invoice_created_at);
          $Invoice_single_value_data = array('Grand_Total'=> $Grand_Total,'Invoice_created_at' => $Invoice_created_at,'Invoice_order_id' => $Invoice_order_id);


          // $Invoice_single_value_data_merge = array_merge($Grand_Total,$Invoice_order_id,$Invoice_created_at);
          
          $product_quantities = $request->input('qty');
          $product_quantities = array('product_quantities' => $product_quantities);
          $product_rates = $request->input('Rate');
          $product_rates = array('product_rates' => $product_rates);
          
          $product_names = $request->input('product_name');
          $product_names = array('product_names' => $product_names);

          $number_of_invoice_items = count($product_names['product_names']);
          
          $order_items_details = array_merge($product_quantities,$product_rates, $product_names);

          $pdf = PDF::loadView('orders.invoice_pdf', compact('Invoice_single_value_data','order_items_details','number_of_invoice_items'));
      // return $pdf->download('all_products.pdf');
      return $pdf->stream();

          
        }
        if($request->printt)
        {
            
            $previous_order_id = DB::table('orders')->where('user_id',$user_id)->max('id');
            $Invoice_order_id = $previous_order_id + 1; 
                
                    $Invoice_created_at = now();
                    
                    $client = new Party([
                       'name'          => 'Umair',
                       'phone'         => '03076666004',
                       'custom_fields' => [
                           'note'        => 'IDDQD',
                           'business id' => '365#GG',
                       ],
                   ]);

                   $customer = new Party([
                       'name'          => 'Umair',
                       'address'       => 'The Green Street 12',
                       'code'          => '#22663214',
                       'custom_fields' => [
                           'order number' => '> 654321 <',
                       ],
                   ]);
                    $items_Count = count($request->product_id);
                    $items = [];

                    for($i = 0; $i<$items_Count; $i++){
                        $product_name = DB::table('products')->where('user_id',$user_id)->where('id',$request->product_id[$i])->select('name')->first();     

                        $object = new InvoiceItem();
                        $object->title = (string)$product_name->name;
                        $object->price_per_unit = $request->Rate[$i];
                        $object->quantity = (float)$request->qty[$i];
                        $items[] = $object;
                        }
                        
                    // }
                   // $items = [
                   //     (new InvoiceItem())
                           // ->title('abc')
                           // // ->description('Your product or service description')
                           // ->pricePerUnit(2)
                   //         ->quantity(3)
                   //         ->discount(0)
                       // (new InvoiceItem())->title('Service 2')->pricePerUnit(71.96)->quantity(2),
                       // (new InvoiceItem())->title('Service 3')->pricePerUnit(4.56),
                       // (new InvoiceItem())->title('Service 4')->pricePerUnit(87.51)->quantity(7)->discount(4)->units('kg'),
                       // (new InvoiceItem())->title('Service 5')->pricePerUnit(71.09)->quantity(7)->discountByPercent(9),
                       // (new InvoiceItem())->title('Service 6')->pricePerUnit(76.32)->quantity(9),
                       // (new InvoiceItem())->title('Service 7')->pricePerUnit(58.18)->quantity(3)->discount(3),
                       // (new InvoiceItem())->title('Service 8')->pricePerUnit(42.99)->quantity(4)->discountByPercent(3),
                       // (new InvoiceItem())->title('Service 9')->pricePerUnit(33.24)->quantity(6)->units('m2'),
                       // (new InvoiceItem())->title('Service 11')->pricePerUnit(97.45)->quantity(2),
                       // (new InvoiceItem())->title('Service 12')->pricePerUnit(92.82),
                       // (new InvoiceItem())->title('Service 13')->pricePerUnit(12.98),
                       // (new InvoiceItem())->title('Service 14')->pricePerUnit(160)->units('hours'),
                       // (new InvoiceItem())->title('Service 15')->pricePerUnit(62.21)->discountByPercent(5),
                       // (new InvoiceItem())->title('Service 16')->pricePerUnit(2.80),
                       // (new InvoiceItem())->title('Service 17')->pricePerUnit(56.21),
                       // (new InvoiceItem())->title('Service 18')->pricePerUnit(66.81)->discountByPercent(8),
                       // (new InvoiceItem())->title('Service 19')->pricePerUnit(76.37),
                       // (new InvoiceItem())->title('Service 20')->pricePerUnit(55.80),
                   // ];
                   // dd($items);
                   $notes = [
                       'Thanks for purchasing from us!',
                       
                   ];
                   $notes = implode("<br>", $notes);

                   $invoice = Invoice::make('receipt')
                       ->series('BIG')
                       // ability to include translated invoice status
                       // in case it was paid
                       // ->status(__('invoices::invoice.paid'))
                       // ->sequence(667)
                       ->serialNumberFormat($Invoice_order_id)
                       // ->serialNumberFormat('{SEQUENCE}/{SERIES}')
                       
                       ->seller($client)
                       ->buyer($customer)
                       // ->date(now()->subWeeks(3))
                       ->date(Carbon::parse($Invoice_created_at))
                       ->dateFormat('m/d/Y')
                       ->payUntilDays(14)
                       // ->currencySymbol('$')
                       // ->currencyCode('USD')
                       // ->currencyFormat('{SYMBOL}{VALUE}')
                       // ->currencyThousandsSeparator('.')
                       // ->currencyDecimalPoint(',')
                       // ->filename($client->name . ' ' . $customer->name)
                       ->addItems($items)
                       ->notes($notes)
                       ->logo(public_path('vendor/invoices/sample-logo.png'))
                       // // You can additionally save generated invoice to configured disk
                       ->save('public');

                       

                   $link = $invoice->url();
                   // Then send email to party with link

                   // And return invoice itself to browser or have a different view
                   return $invoice->stream();
        }
        else
        {
            $Grand_Total = $request->input('GrandTotal');
            $Total_Profit = $request->input('TotalProfit');
            $product_quantities = $request->input('qty');
            $product_rates = $request->input('Rate');
            $product_ids = $request->input('product_id');
            $previous_customer_id = DB::table('orders')->max('customer_id');
            $current_customer_id = $previous_customer_id + 1;
            $inserted_order_id = DB::table('orders')->insertGetId(
                [
                  // 'profit' => $Total_Profit,
                 'grand_total' => $Grand_Total,
                 // 'user_id' => $user_id,
                 'status' =>'Pending',
                 'payment_id' => '1',
                 'customer_id' => $current_customer_id,
                 'created_at' => now(),
                 'updated_at' => now()
            ]);
            if($inserted_order_id)
                $customer_inserted = DB::table('customers')->insert(
                  [ 'billing_firstName' => 'onShopCustomer',
                    'billing_lastName' => 'onShopCustomer',
                    'username' => 'onShopCustomer',
                    'email' => 'onShopCustomer',
                    'billing_address1' => 'onShopCustomer',
                    'billing_address2' => 'onShopCustomer',
                    'billing_country' => 'onShopCustomer',
                    'billing_state' => 'onShopCustomer',
                    'billing_zip' => 'onShopCustomer',
                    'shipping_firstName' => 'onShopCustomer',
                    'shipping_lastName' => 'onShopCustomer',
                    'shipping_address1' => 'onShopCustomer',
                    'shipping_address2' => 'onShopCustomer',
                    'shipping_country' => 'onShopCustomer',
                    'shipping_state' => 'onShopCustomer',
                    'shipping_zip' => 'onShopCustomer',
                    // 'id' => 'onShopCustomer',
                    'created_at' => now(),
                    'updated_at' => now()
                  ]);
                foreach($product_quantities as $index=>$product_qty){
                    $order_item_inserted = DB::table('order_item')->insert(
                        ['order_id' => $inserted_order_id,
                        'product_id' => $product_ids[$index],
                        'user_id' => $user_id,
                        'product_sale_rate' => $product_rates[$index],
                        'product_qty' => $product_qty,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // $updated = DB::table('products')
                    //               ->where('id', $product_ids[$index])
                    //               ->where('user_id',$user_id)
                    //               ->decrement('stock', $product_qty);
                }
            // advanced invoice code

            // $order_details = DB::table('order_item')
            //         ->join('orders','order_item.order_id','=','orders.id')
            //         ->leftjoin('products','order_item.product_id','=','products.id')
            //         ->select('order_item.*','orders.*','products.name as product_name')
            //         ->where('order_item.order_id','=',$inserted_order_id)
            //         ->get(); 
            //         $Invoice_order_id = $order_details[0]->order_id;
            //         $Invoice_created_at = $order_details[0]->created_at;
                    
            //         $client = new Party([
            //            'name'          => 'Umair',
            //            'phone'         => '03076666004',
            //            'custom_fields' => [
            //                'note'        => 'IDDQD',
            //                'business id' => '365#GG',
            //            ],
            //        ]);

            //        $customer = new Party([
            //            'name'          => 'Umair',
            //            'address'       => 'The Green Street 12',
            //            'code'          => '#22663214',
            //            'custom_fields' => [
            //                'order number' => '> 654321 <',
            //            ],
            //        ]);
            //         $items = [];
            //         foreach($order_details as $order_detail){

            //             $object = new InvoiceItem();
            //             $object->title = $order_detail->product_name;
            //             $object->price_per_unit = $order_detail->product_sale_rate;
            //             $object->quantity = (float)$order_detail->product_qty;
            //             $items[] = $object;
            //         }
            //        // var_dump($items);
                   // $items = [
                   //     (new InvoiceItem())
                   //         ->title('abc')
                   //         // ->description('Your product or service description')
                   //         ->pricePerUnit(2)
                   //         ->quantity(3)
                   //         ->discount(0)
                       // (new InvoiceItem())->title('Service 2')->pricePerUnit(71.96)->quantity(2),
                       // (new InvoiceItem())->title('Service 3')->pricePerUnit(4.56),
                       // (new InvoiceItem())->title('Service 4')->pricePerUnit(87.51)->quantity(7)->discount(4)->units('kg'),
                       // (new InvoiceItem())->title('Service 5')->pricePerUnit(71.09)->quantity(7)->discountByPercent(9),
                       // (new InvoiceItem())->title('Service 6')->pricePerUnit(76.32)->quantity(9),
                       // (new InvoiceItem())->title('Service 7')->pricePerUnit(58.18)->quantity(3)->discount(3),
                       // (new InvoiceItem())->title('Service 8')->pricePerUnit(42.99)->quantity(4)->discountByPercent(3),
                       // (new InvoiceItem())->title('Service 9')->pricePerUnit(33.24)->quantity(6)->units('m2'),
                       // (new InvoiceItem())->title('Service 11')->pricePerUnit(97.45)->quantity(2),
                       // (new InvoiceItem())->title('Service 12')->pricePerUnit(92.82),
                       // (new InvoiceItem())->title('Service 13')->pricePerUnit(12.98),
                       // (new InvoiceItem())->title('Service 14')->pricePerUnit(160)->units('hours'),
                       // (new InvoiceItem())->title('Service 15')->pricePerUnit(62.21)->discountByPercent(5),
                       // (new InvoiceItem())->title('Service 16')->pricePerUnit(2.80),
                       // (new InvoiceItem())->title('Service 17')->pricePerUnit(56.21),
                       // (new InvoiceItem())->title('Service 18')->pricePerUnit(66.81)->discountByPercent(8),
                       // (new InvoiceItem())->title('Service 19')->pricePerUnit(76.37),
                       // (new InvoiceItem())->title('Service 20')->pricePerUnit(55.80),
                   // ];
                   // dd($items);
                   // $notes = [
                   //     'Thanks for purchasing from us!',
                       
                   // ];
                   // $notes = implode("<br>", $notes);

                   // $invoice = Invoice::make('receipt')
                       // ->series('BIG')
                       // ability to include translated invoice status
                       // in case it was paid
                       // ->status(__('invoices::invoice.paid'))
                       // ->sequence(667)
                       // ->serialNumberFormat($Invoice_order_id)
                       // ->serialNumberFormat('{SEQUENCE}/{SERIES}')
                       
                       // ->seller($client)
                       // ->buyer($customer)
                       // ->date(now()->subWeeks(3))
                       // ->date(Carbon::parse($Invoice_created_at))
                       // ->dateFormat('m/d/Y')
                       // ->payUntilDays(14)
                       // ->currencySymbol('$')
                       // ->currencyCode('USD')
                       // ->currencyFormat('{SYMBOL}{VALUE}')
                       // ->currencyThousandsSeparator('.')
                       // ->currencyDecimalPoint(',')
                       // ->filename($client->name . ' ' . $customer->name)
                       // ->addItems($items)
                       // ->notes($notes)
                       // ->logo(public_path('vendor/invoices/sample-logo.png'))
                       // // You can additionally save generated invoice to configured disk
                       // ->save('public');

                       

                   // $link = $invoice->url();
                   // Then send email to party with link

                   // And return invoice itself to browser or have a different view
                   // return $invoice->stream();
            // end of advanced invoice code
                   
            if($order_item_inserted){
                $request->session()->flash('alert-success', 'Order was added successfully!');
                 return redirect()->route("calculations.calculate");

            }

            // $description = $request->input('product_description');
            // $purchase_price = $request->input('product_purchase_price');
            // $regular_price = $request->input('product_regular_price');
            // $sale_price = $request->input('product_sale_price');
            // $quantity = $request->input('product_quantity');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
      $user_id = $this->getLoggedInUserId();
      $order_details = DB::table('order_item')->join('products', function ($join){ $join->on('order_item.product_id', '=', 'products.id')->on('order_item.user_id', '=', 'products.user_id');})->select('products.name as product_name','order_item.product_qty','order_item.product_sale_rate')->addselect(DB::raw('order_item.product_qty * order_item.product_sale_rate as product_total'))->where('order_item.user_id','=',$user_id)->where('order_item.order_id',$id)->get();

      // SELECT sum(product_sale_rate * product_qty) FROM `order_item` group by order_id
      
      // select order_item.order_id, orders.profit, orders.grand_total, orders.created_at, orders.updated_at,orders.status, order_item.product_qty as Quantity, order_item.product_sale_rate as Rate, products.updated_at, products.name , (SELECT sum(OI.product_sale_rate * OI.product_qty) FROM `order_item` as OI WHERE OI.order_id = order_item.order_id group by order_id ) as Munafa from orders inner join order_item on orders.id = order_item.order_id inner join products on order_item.product_id = products.id and order_item.user_id = products.user_id 

//        DB::table('order_item')->join('products','order_item.product_id','p
// roducts.id')->join('users','order_item.user_id','users.id')->where('ord
// er_item.user_id','1')->where('products.user_id','1')->select('users.nam
// e as username','order_item.product_sale_rate','order_item.product_qty',
// 'products.name as product_name',DB::raw('select order_item.product_sale
// _rate as sarate'))->get();
      // $order_details =  DB::table('order_item')->join('orders', 'order_item.order_id','=','orders.id')->join('products', 'order_item.product_id','=','products.id')->select('products.name as product_name','order_item.product_qty','order_item.product_sale_rate')->where('orders.user_id',$this->user_id)->where('products.user_id',$this->user_id)->where('order_item.order_id',$id)->get();

      // $order_details = DB::table('order_item')->join('products','order_item.product_id','products.id')->join('users','order_item.user_id','users.id')->where('order_item.user_id',$this->user_id)->where('products.user_id',$this->user_id)->select(DB::raw('sum(product_sale_rate*product_qty) as grand_total'),'users.name as username','order_item.product_sale_rate','order_item.product_qty','products.name as product_name')->get();

      $grand_total = DB::table('order_item')->select(DB::raw('sum(product_sale_rate*product_qty) as grand_total'))->where('user_id',$user_id)->where('order_id',$id)->get();
      
         return view('orders.order_detail' , compact('order_details','grand_total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        DB::table('order_item')->where('order_id', $id)->delete();
        return redirect()->route("order.index");
    }
}
