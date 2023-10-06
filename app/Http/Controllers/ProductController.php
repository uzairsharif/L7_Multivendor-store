<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Http\Requests\updateProductRecord;
use App\Http\Requests\ReturnProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Session;
use App\Cart;
use Auth;


class ProductController extends Controller
{
    public $low_limit = 10;
    public $user_id;
    public function __construct(){
          $this->middleware(function ($request, $next) {
            $this->user_id = Auth::user()->id;
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        // $data = array(
        //   'id'=>'6',
        //   'name'=>'Awais',
        //   'description'=>'xyz',
        //   'email'=>'o@0.com',
        //   'contact_no'=>'9990',
        //   'created_at'=>now(),
        //   'updated_at'=>now()
        // );
        //  $buyers = Db::table('buyers')->insert($data);
        //  dd($buyers);
      // $order_info = DB::table('orders')
      // ->join('order_item', 'orders.id', '=', 'order_item.order_id')->get(); 
      
      //   die();

      // $products = Product::all()->where('user_id', '=' ,$this->user_id);
      // dd('abc');
      
      $products = DB::select ('select my_union.product_id as id,products.name,products.description,products.created_at,products.updated_at,products.img_upload_url, my_union.user_id,sum(my_union.product_purchase_qty ) as stock , (select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price, (select product_sale_price from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price from(select product_id,user_id,product_purchase_qty,product_purchase_rate from purchase_body where deleted_at IS NULL union select product_id, user_id, -product_qty, product_sale_rate from order_item where deleted_at IS NULL )my_union left join products on my_union.product_id = products.id and my_union.user_id = products.user_id where products.user_id ='.$this->user_id.' and products.deleted_at IS NULL group by product_id,user_id');
      
        return view('product.product' , compact('products'));
    }
    public function createPDF() {
      $products = DB::select ('select my_union.product_id as id,products.name,products.description,products.created_at,products.updated_at,products.img_upload_url, my_union.user_id,sum(my_union.product_purchase_qty ) as stock , (select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price, (select product_sale_price from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price from(select product_id,user_id,product_purchase_qty,product_purchase_rate from purchase_body where deleted_at IS NULL union select product_id, user_id, -product_qty, product_sale_rate from order_item where deleted_at IS NULL )my_union left join products on my_union.product_id = products.id and my_union.user_id = products.user_id where products.user_id ='.$this->user_id.' and products.deleted_at IS NULL group by product_id,user_id');
      // $data = Product::all()->where('user_id',$this->user_id);
      // $products = $data->all();

      // dd($products);
      // $pdf = App::make('dompdf.wrapper');
      // $pdf->loadHTML('<h1>Test</h1>');
      // return $pdf->stream();
      // view()->share('products', $products);
      $pdf = PDF::loadView('product.product_pdf_download', compact('products'));
      // return $pdf->download('all_products.pdf');
      return $pdf->stream();

      
      // $data = Product::all();
      // // share data to view
      // view()->share('products',$data->all());
      // $pdf = PDF::loadView('product.product_pdf_download', $data->all());

      // // download PDF file with download method
      // return $pdf->download('pdf_file.pdf');
    }
    
    public function low_stock_PDF() {

      // $data = Product::all()->where('user_id',$this->user_id)->where('stock','<=',$this->low_limit);
      // // dd($data);
      // $data = DB::select ('select my_union.product_id as id,products.name,products.description,products.created_at,products.updated_at,products.img_upload_url, my_union.user_id,sum(my_union.product_purchase_qty) as stock , (select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price, (select product_sale_price from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price from(select product_id,user_id,product_purchase_qty,product_purchase_rate from purchase_body union select product_id, user_id, -product_qty, product_sale_rate from order_item)my_union left join products on my_union.product_id = products.id and my_union.user_id = products.user_id where products.user_id ='.$this->user_id.' and products.deleted_at IS NULL group by product_id,user_id');
      // // dd($data);
      $query = DB::table('products')
          ->select(
              'my_union.product_id as id',
              'products.name',
              'products.description',
              'products.created_at',
              'products.updated_at',
              'products.img_upload_url',
              'my_union.user_id',
              DB::raw('sum(my_union.product_purchase_qty) as stock'),
              DB::raw('(select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price'),
              DB::raw('(select product_sale_price from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price')
          )
          ->from(function ($query) {
              $query->select('product_id', 'user_id', 'product_purchase_qty', 'product_purchase_rate')
                  ->from('purchase_body')
                  ->unionAll(DB::table('order_item')
                      ->select('product_id', 'user_id', DB::raw('-product_qty as product_purchase_qty'), 'product_sale_rate')
                  );
          }, 'my_union')
          ->leftJoin('products', function ($join) {
              $join->on('my_union.product_id', '=', 'products.id')
                  ->on('my_union.user_id', '=', 'products.user_id');
          })
          ->where('products.user_id', $this->user_id)
          ->whereNull('products.deleted_at')
          ->groupBy('product_id', 'user_id')
          ->havingRaw('stock < ?', [$this->low_limit]);

      $data = $query->get();
      $data = (object) $data;
      $products = $data->all();
      // $pdf = App::make('dompdf.wrapper');
      // $pdf->loadHTML('<h1>Test</h1>');
      // return $pdf->stream();
      // view()->share('products', $products);
      $pdf = PDF::loadView('product.low_stock_products_pdf', compact('products'));
      // return $pdf->download('all_products.pdf');
      return $pdf->stream();

      
      // $data = Product::all();
      // // share data to view
      // view()->share('products',$data->all());
      // $pdf = PDF::loadView('product.product_pdf_download', $data->all());

      // // download PDF file with download method
      // return $pdf->download('pdf_file.pdf');
    }
    public function low_stock_product()
    {
        $products = DB::select ('select my_union.product_id as id,products.name,products.description,products.created_at,products.updated_at,products.img_upload_url, my_union.user_id,sum(my_union.product_purchase_qty) as stock , (select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price, (select product_sale_price from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price from(select product_id,user_id,product_purchase_qty,product_purchase_rate from purchase_body where deleted_at IS NULL union select product_id, user_id, -product_qty, product_sale_rate from order_item where deleted_at IS NULL)my_union left join products on my_union.product_id = products.id and my_union.user_id = products.user_id where products.user_id ='.$this->user_id.' and products.deleted_at IS NULL group by product_id,user_id having stock < 10');
        // dd($products);
        // $products = DB::table('products')->where('stock','<=',$low_limit)->get();
        // $products = Product::all();  // eloquest model ko use kr k kia tha.
        return view('product.LowStockProduct' , compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function next_product_id(){
      $max_product_id = Product::withTrashed()->where('user_id','=', $this->user_id)->max('id');
      return $max_product_id;
    }
    public function create(Request $request)
    {
      $product_categories = Category::all();

      $max_product_id = $this->next_product_id();
        // $request->flash();
        $max_product_id = Product::withTrashed()->where('user_id', '=' , $this->user_id)->max('id');
        

        //ooper wala code use ni kia kiun k hm na softDelete kia hua hai
        // or us sa Eloquent use krtay huay softdeleted rows count nhi hn gi.
        // $max_product_id = DB::table('products')->max('id');
         // \DB::table('orders')->where('id', \DB::raw("(select max(`id`) from orders)"))->get();
        
        $new_product_id = $max_product_id + 1;
        
        return view('product.create', compact('new_product_id','product_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNewProduct $request)
    {
      // dd($request->product_category);
      // $request->file('product_image')->store('images');
      // ooper wali line storage/app/images k andar image ko store kray gi.
      // aur is ma file ka name auto generated key hoga.
      // $request->file('product_image')->store('images','public');
      // ye storage/app/public/images ma file ko store kray ga.
      // public jo likha hai wo filesystems.php sa aya hai matlab k 
      // aap default, public ya s3 use kr sktay hain.
      // $request->file('product_image')->storeAs('images', 'logo.jpg', 'public');
      // ye storeAs jo hai, ye ab file ko logo.jpg k name sa hi store kray ga. ya phir file ko jo name hoga usi name sa store hogi. is ko hm profile image bnanay k liay use krtay hain.
        $id = $request->input('product_id');
        $name = $request->input('product_name');
        $category_id = $request->input('product_category');
        
        $description = $request->input('product_description');
        $purchase_price = $request->input('product_purchase_price');
        $percentage = 40;
        $sale_price = $purchase_price + $purchase_price*$percentage/100;
        $quantity = $request->input('product_quantity');
        
        // $url = '';
        if ($request->hasFile('product_image')) {
        //  Let's do everything here
            if ($request->file('product_image')->isValid()) {
                //
                $validated = $request->validate([
                    // 'name' => 'string|max:40',
                    'product_image' => 'mimes:jpeg,png|max:1014',
                ]);

                $extension = $request->product_image->extension();
                $custom_product_name = "product_$id.$extension";
                $custom_folder_name = "user_$this->user_id";
                $request->product_image->storeAs($custom_folder_name, $custom_product_name,'public');
                
                $storage_path = $custom_folder_name.'/'.$custom_product_name;
                $url = Storage::url($storage_path);
                
                
    //         $file = Product::create([
    //            'name' => $validated['name'],
    //             'url' => $url,
    //         ]);
    //         dd($file);
    //         Session::flash('success', "Success!");
    //         return \Redirect::back();
            }
        }
    // abort(500, 'Could not upload image :(');

       
  // ye commented code us liay tha k product exist krti hai aur phir
  //hm ussay update kr rhay thay  
        // $existing_products_ids = DB::table('products')->select('id','stock')->get();
        $product_exists = false;
        // foreach($existing_products_ids as $single_product){
        //     if($id == $single_product->id){
        //         $new_purchase_price = $purchase_price;
        //         $new_quantity = $single_product->stock + $quantity;
        //         $updated = DB::table('products')
        //             ->where('id', $id)
        //             ->update(['stock' => $new_quantity,
        //                       'purchased_price' => $new_purchase_price,
        //                       'Sale_price' => $sale_price
        //         ]);
        //         $product_exists = true;
        //         $request->session()->flash('alert-danger', 'Product was updated as it already exists!');
        //         return redirect()->route("product.create");
        //         break;
        //     }
        // }
  // yahan tak product ko update krnay ka code tha.....
        if(!$product_exists){
            $inserted = DB::insert('insert into products (id, name,category_id, description, purchased_price, Sale_price, stock, img_upload_url, created_at, updated_at, user_id) values (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id, $name, $category_id, $description, $purchase_price, $sale_price, $quantity, $url, now(), now(), $this->user_id ]);
            if($inserted)
                $request->session()->flash('alert-success', 'Product was added successfully!');
                 return redirect()->route("product.create");
        }    
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // dd(Session::get('cart'));
        return view('product.show', compact('product'));
    }
    // public function addToCart(Product $product){//use App\Cart; use kr  lain. namespace App tha. use Session bhi krain ooper kiun k hm na session bhi use krna hai.
    //         $oldCart = Session::has('cart')? Session::get('cart') : null;    
    //         $cart = new Cart($oldCart);  // agar old cart k andar kuch nhi hoga to construct kuch nhi kray ga aur agar hoga phir construct apna kam kray ga.
    //         $cart->addProduct($product);
    //         Session::put('cart', $cart);   // ab hm na session create kia cart k name sa aur us ma $cart ka data dal dia.
    //         return back()->with('message', "Product $product->title has been successfully added to Cart");
    // }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function edit(Product $product) model binding ka kia faida hona tha.
    public function edit($id)
    {

      // $product = Product::where(['id'=>$id, 'user_id'=>$this->user_id])->first();
      $user_id = Auth::id();
      $product = DB::select('select products.id,products.name,products.description,purchase_body.product_sale_price from products inner join purchase_body on products.user_id = purchase_body.user_id and products.id = purchase_body.product_id where products.id = '.$id.' and products.user_id='.$user_id.' and purchase_body.created_at = (select max(created_at) from purchase_body where purchase_body.user_id='.$user_id.' and purchase_body.product_id = '.$id.')');
      $product = (object) $product[0];
      // dd($product);
        // $product = Product::find($id)->where('user_id', '=', $this->user_id)->whereNull('deleted_at')->first();
        
        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Product $product)
    public function update($id, updateProductRecord $request)
    {

        // $name = $request->input('product_name');
        $description = $request->input('product_description');
        // $purchase_price = $request->input('product_purchase_price');
        // $percentage = 40;
        $sale_price = $request->input('product_sale_price');
        // $quantity = $request->input('product_quantity');
        $purchase_body_updated = DB::table('purchase_body')
              ->where(['product_id'=>$id, 'user_id'=> $this->user_id])
              
              
              ->update(['product_sale_price' => $sale_price,            
           ]);
        $products_updated = DB::table('products')
              ->where(['id'=>$id, 'user_id'=>$this->user_id])
              ->update(['description' => $description,
            ]);
        // dd($products_updated);
        if($purchase_body_updated || $products_updated)
            $request->session()->flash('alert-success', 'Product record was updated successfully!');
             return redirect()->route("product.edit", $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Product $product)
    public function destroy($id)
    {   

        $product = Product::where(['id'=>$id, 'user_id'=>$this->user_id])->first();
        $file_delete_path = str_replace('/storage','public',$product->img_upload_url);
        Storage::delete($file_delete_path);
        
        // Storage::deleteDirectory('public/user_1');
        
        $product->delete();
        DB::table('purchase_body')
           ->where('product_id', '=', $id)
           ->where('user_id', '=', $this->user_id)
           ->update(['deleted_at' => now()]);
        DB::table('order_item')
           ->where('product_id', '=', $id)
           ->where('user_id', '=', $this->user_id)
           ->update(['deleted_at' => now()]);

        return redirect()->route("product.index");
    }
    public function del_low_stock_product($id)
    {
      $product = Product::where(['id'=>$id, 'user_id'=>$this->user_id])->delete();
        
        return redirect()->route("product.low_stock_products");
    }
    public function download_file($id){
       $product = Product::where(['id'=>$id, 'user_id'=>$this->user_id])->first();
        $file_delete_path = str_replace('/storage','public',$product->img_upload_url);
      // return Storage::download('public/user_1/product_1.png');
      return Storage::download($file_delete_path);
    }
}
