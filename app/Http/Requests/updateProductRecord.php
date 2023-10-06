<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class updateProductRecord extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return arrays
     */
    public function rules()
    {   
        // this below line is accessing the parameter given in the route. our product.update route is "Product/{product}}" and hence we are able to access this route.
        $product_id = $this->route('product');
        // dd($product_id);
        $user_id = Auth::id();
        $latest_product_purchase_price = DB::select('select product_purchase_rate from purchase_body where product_id = '.$product_id.' and user_id = '.$user_id.' and created_at = (select max(created_at) from purchase_body where product_id = '.$product_id.' and user_id = '.$user_id.')');
        $latest_product_purchase_price = $latest_product_purchase_price[0]->product_purchase_rate;
        $min_sale_price = $latest_product_purchase_price + $latest_product_purchase_price* 0.4;
        
        return [
            // 'product_id' => 'required|string|max:3',
            // 'product_name' => 'required|string|max:50',
            'product_description' => 'required|string|max:50',
            'product_sale_price' => 'required|integer|min:'.$min_sale_price.'',
            // 'product_regular_price' => 'required|integer',
            // 'product_sale_price' => 'required|integer',
            // 'product_quantity' => 'required|integer|min:1'
        ];
    }
    public function attributes(){
        return [
            'product_name' => 'name of product'
        ];
    }
    public function messages(){
            $a ='';
        return [
            'product_name.required'=> 'product name '.$a.' is missing'
        ];
    }
}
