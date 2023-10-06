<?php

namespace App\Http\Controllers;
use Session;
use Stripe;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StripeController extends Controller
{
    // public function stripe()
    // {
    //     return view('stripe');
    // }

    public function stripePost(Request $request)
    {
        
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $charge = Stripe\Charge::create ([
                "amount" => $request->pay_price*100,
                "currency" => "PKR",
                "source" => $request->stripeToken,
                "description" => "This is test payment",

            ]);   
        if($charge->paid){
            // $paid_order_id = session('paid_order_id');
            $paid_order_id = $request->session()->get('paid_order_id');
            
            DB::table('orders')->where('id',$paid_order_id)->update(['payment_id'=>'1']);
        }


        $request->session()->forget('cart');
        return redirect('all_products')->with('message', 'Payment Successful!');
        // Session::flash('success', 'Payment Successful !');
           
        // return back();
    }
}