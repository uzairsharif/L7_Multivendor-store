<?php
namespace App;
use Illuminate\Support\Arr;
class Cart
	{
	private $contents;
	private $totalQty;
	private $totalPrice;
	public function __construct($oldCart){
		if($oldCart){
			$this->contents = $oldCart->contents;
			$this->totalQty = $oldCart->totalQty;
			$this->totalPrice = $oldCart->totalPrice;
		}
	}
	public function addProduct($product, $qty){

		$products = ['qty' => 0, 'price' => $product->Sale_price, 'product' => $product];

		
		if($this->contents){
		// dd($this->contents[$product->id]);
			if(array_key_exists($product->id, $this->contents)){
				foreach($this->contents as $user_id => $users_data){
					 
					if(array_key_exists($product->user_id, $users_data)){
						// dd('aleary in the cart');
						$products = $this->contents[$product->id][$product->user_id];
						// dd($products);
						// break;
					}

				} 	
			}
			 
		}

		$products['qty']+=$qty;
		$products['price'] = $product->Sale_price*$products['qty'];
		
		$this->contents[$product->id][$product->user_id] = $products;

		$this->totalQty+=$qty;
		$this->totalPrice +=$product->Sale_price;
	}
	public function updateProduct($product, $qty){
		
		if($this->contents){
			if(array_key_exists($product->id, $this->contents)){
				foreach($this->contents as $user_id => $users_data){
					 
					if(array_key_exists($product->user_id, $users_data)){
					 
						$products = $this->contents[$product->id][$product->user_id];
						 
					}

				}
			 

			}
		}
		
		$this->totalQty -= $products['qty'];
		$this->totalPrice -= $products['price'];
		$products['qty'] = $qty;

		$products['price'] = $product->Sale_price*$qty;
		$this->totalPrice += $products['price'];
		$this->totalQty += $qty;
		$this->contents[$product->id][$product->user_id] = $products;
		 
	}

	public function removeProduct($product){
 
		if($this->contents){
			// dd($this->contents);
			if(array_key_exists($product->id, $this->contents)){
				foreach($this->contents as $user_id => $users_data){
					 
					if(array_key_exists($product->user_id, $users_data)){
						 
						$rProduct = $this->contents[$product->id][$product->user_id];
					 	 
						$this->totalQty -= $rProduct['qty'];
						$this->totalPrice -= $rProduct['price'];
					 	Arr::forget($this->contents, "$product->id.$product->user_id" );
					  	if(empty($this->contents[ "$product->id"] ) )
					  		Arr::forget($this->contents, "$product->id");
					 	 

					  	if(empty($this->contents))
			 			return true;
					  		  

					 	break;
						  
					}

				}
			 

			}

		}

	}
	public function getContents(){
		return $this->contents;
		}
	public function getTotalQty(){
		return $this->totalQty;
	}
	public function getTotalPrice(){
		return $this->totalPrice;
	}
}