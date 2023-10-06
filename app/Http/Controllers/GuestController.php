<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;
use App\Cart;
use App\Category;
use Session;

class GuestController extends Controller
{
    public function index(){
    	return view('guest.guestHome');
    }

    // public function product_card()
    // {
    //     $products = Product::all();
    //     $all_categories = Category::pluck('category_name')->all();
    //     return view('product.product_card' ,  compact('products','all_categories'));
    // }
    public function categorized_products($category_name = null){
        if(!$category_name)
            {
                $products = DB::select ('select my_union.product_id as id,products.name,products.img_upload_url, my_union.user_id,sum(my_union.product_purchase_qty) as stock ,(select pb_in.updated_at from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as updated_at, (select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price, (select product_purchase_rate + product_purchase_rate*0.4 from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price from(select product_id,user_id,product_purchase_qty,product_purchase_rate from purchase_body union select product_id, user_id, -product_qty, product_sale_rate from order_item)my_union left join products on my_union.product_id = products.id and my_union.user_id = products.user_id group by product_id,user_id');
                
                
                

                // $products = DB::table('purchase_body')->join('products',function ($join){
                //         $join->on('purchase_body.user_id', '=', 'products.user_id')->on('purchase_body.product_id', '=' , 'products.id');
                // })->select('purchase_body.product_id as id','purchase_body.user_id','products.name','products.img_upload_url','purchase_body.product_purchase_rate as Sale_price','purchase_body.updated_at')->addSelect(DB::raw('sum(purchase_body.product_purchase_qty) as stock'))->groupby('purchase_body.product_id','purchase_body.user_id')->get();
                // dd($products);
                // dd($products);
                // $products = DB::table('purchase_body')->select('purchase_body.product_id as id','purchase_body.user_id','products.name','products.img_upload_url')->addSelect(DB::raw('sum(product_purchase_qty) as stock'))->groupby('id','user_id','products.name','products.img_upload_url');
                // $products = $products->join('products', function ($join) {
                //         $join->on('purchase_body.user_id', '=', 'products.user_id')->on('purchase_body.product_id', '=' , 'products.id');
                // })
                // ->get();            
                // dd($products); 
                // $products = Product::all()->where('stock','<>','0');
                
                $all_categories = Category::pluck('category_name')->all();
                return view('product.product_card' ,  compact('products','all_categories'));
            }
        else
            {
                $category_id = Category::where('category_name',$category_name)->first()->id;
                $products = DB::select ('select my_union.product_id as id,products.name,products.img_upload_url, my_union.user_id,sum(my_union.product_purchase_qty) as stock ,(select pb_in.updated_at from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as updated_at, (select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price, (select product_purchase_rate + product_purchase_rate*0.4 from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price from(select product_id,user_id,product_purchase_qty,product_purchase_rate from purchase_body union select product_id, user_id, -product_qty, product_sale_rate from order_item)my_union left join products on my_union.product_id = products.id and my_union.user_id = products.user_id where category_id = '.$category_id.' group by product_id,user_id');
                // dd($products);
                // $products = Product::all()->where('category_id', $category_id);

                
                $all_categories = Category::pluck('category_name')->all();
                return view('product.product_card' , compact('products','all_categories'));
        }
      
    }
    public function cart(){
        if(!Session::has('cart')){
            return view('product.cart');
        }
        $cart = Session::get('cart');
        return view('product.cart', compact('cart'));
    }
    public function addToCart( $product_id, $user_id, Request $request){ 
       // $request bhi use kr lia kiun k hm na post bhi to krna hai bad ma.... abhi get hai.
         
        // $product = Product::where('id',$product_id)->where('user_id',$user_id)->first();
        // dd($product);
        $product = DB::select ('select my_union.product_id as id,products.name,products.description,products.img_upload_url, my_union.user_id,sum(my_union.product_purchase_qty) as stock , (select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price, (select product_purchase_rate + product_purchase_rate*0.4 from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price from(select product_id,user_id,product_purchase_qty,product_purchase_rate from purchase_body union select product_id, user_id, -product_qty, product_sale_rate from order_item)my_union left join products on my_union.product_id = products.id and my_union.user_id = products.user_id where my_union.product_id ='.$product_id.' and my_union.user_id ='.$user_id.' group by product_id,user_id'); 
        $product = $product[0];

        $oldCart = Session::has('cart') ? Session::get('cart') :null;

        $qty = $request->qty ? $request->qty: 1;
        // dd($oldCart);
        
        $cart = new Cart($oldCart); 
        
        $cart->addProduct($product, $qty);
        
        Session::put('cart', $cart);
        
        return back()->with('message', "Product $product->name has been successfully added to Cart");
    }
    public function removeProduct($product_id, $user_id ){
         
        $product = Product::where('id',$product_id)->where('user_id',$user_id)->first();
        $oldCart = Session::has('cart') ? Session::get('cart'):null;
        $cart =  new Cart($oldCart);
         
        $cart_empty = $cart->removeProduct($product);
          
        if($cart_empty ==  true  ) 
            Session::forget('cart');    
        else
            Session::put('cart', $cart);
        return back()->with('message', "Product $product->title has been successfully removed From the Cart");
    }   
    public function updateProduct( $product_id, $user_id  , Request $request){
        $stock  = DB::select('select sum(product_purchase_qty) as stock from(select product_id,user_id,product_purchase_qty from purchase_body union all select product_id, user_id, -product_qty from order_item)t where product_id ='.$product_id.' AND user_id ='.$user_id.' group by product_id,user_id');
        $stock = $stock[0]->stock;
        // dd($stock);
        // $stock = DB::table('products')->where('id', $product_id)->where('user_id',$user_id)->value('stock');
       
        if($request->qty > $stock)
            return back()->with('message', "Requested quantity is greater than its available stock");
        else
            {
            $product = DB::select ('select my_union.product_id as id,products.name,products.img_upload_url, my_union.user_id,sum(my_union.product_purchase_qty) as stock , (select product_purchase_rate from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as purchased_price, (select product_purchase_rate + product_purchase_rate*0.4 from purchase_body pb_in where pb_in.product_id = my_union.product_id and pb_in.user_id = my_union.user_id order by created_at desc limit 1) as Sale_price from(select product_id,user_id,product_purchase_qty,product_purchase_rate from purchase_body union select product_id, user_id, -product_qty, product_sale_rate from order_item)my_union left join products on my_union.product_id = products.id and my_union.user_id = products.user_id where my_union.product_id ='.$product_id.' and my_union.user_id ='.$user_id.' group by product_id,user_id');
            $product = (object) $product[0];
            // $product = Product::where('id',$product_id)->where('user_id',$user_id)->first();
            // dd($product);
            $oldCart = Session::has('cart') ? Session::get('cart') : null;
            $cart = new Cart($oldCart);
            $a = $cart->updateProduct($product,$request->qty);

            Session::put('cart', $cart);
            return back()->with('message', "Product $product->name has been successfully Updated in the Cart");
            }
    }

}
