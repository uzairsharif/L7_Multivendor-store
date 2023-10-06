<?php
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Products;
use App\Http\Controllers\StripeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth','can:isAllowed,"admin:Subscriber"','password.confirm','verified'])->group(function () {
	
    Route::resource( 'product', 'ProductController');
    
    Route::get('purchase' , function() {
    	return view('purchase.purchasing');
    });
    Route::get('purchases' , function() {
    	return view('purchase.all_purchases');
    });
    Route::get('sales' , function() {
    	return view('sale.all_sales');
    });
    Route::get('sale' , function() {
    	return view('sale.create_sale');
    });
    Route::get('product_return', function () {
    	return view('product.return');
	});
	Route::resource('order', 'OrderController');
	Route::get('Low_Product' , 'ProductController@low_stock_product')->name('product.low_stock_products')->middleware('auth');
	Route::post('Del_Low/{id}' , 'ProductController@del_low_stock_product')->name('product.delete_from_low_stock');
	Route::get('orderPrint', 'OrderController@print')->name('order.print');
	Route::post('DoCheckout', 'OrderController@DoCheckout')->name('order.DoCheckout');
	Route::get('profit' , 'OrderController@profit')->middleware('auth');
	Route::get('calculations' , 'CalculationController@calculate')->middleware('auth')->name("calculations.calculate");
	Route::get('/download_all_products' , 'ProductController@createPDF');
	Route::get('/download_low_stock_products' , 'ProductController@low_stock_PDF');
	Route::get('/products', [\App\Http\Livewire\Products::class,'__invoke']);
	Route::get('/contactsearchbar', [\App\Http\Livewire\ContactSearchBar::class,'__invoke']);
});

Route::get('product_category/{category_name?}', 'GuestController@categorized_products')->name('categorized_products'); 
Route::get('all_products', 'GuestController@categorized_products')->name('products_page');
  
Route::get('checkout', 'OrderController@checkout')->name('orderCheckout');
// Route::post('checkout', 'OrderController@jazzcash_checkout')->name('checkout.jazzcash_checkout');
Route::post('checkout', 'OrderController@stripe_checkout')->name('checkout.stripe_checkout');

Route::get('/cart', 'GuestController@cart')->name('product.cart');
Route::post('/remove/{product}/{user_id}', 'GuestController@removeProduct')->name('cart.remove');
Route::post('/update/{product}/{user_id}', 'GuestController@updateProduct')->name('cart.update');
// Route::get('/addToCart/{id}/{user_id}', 'GuestController@addToCart')->name('product.addToCart');
 
Route::get('/addToCart/{product}/{user_id}', 'GuestController@addToCart')->name('product.addToCart');
 

Route::get('/downloadfile/{id}', 'ProductController@download_file')->name('product.download');

Route::get('/', 'GuestController@index')->name('guestHomeRoute');
Route::group(['prefix' => 'admin'], function () {

    // Auth::routes();
    Auth::routes(['verify'=>true]);

});
Route::view('boostrap-modal','livewire.home');
Route::view('users','livewire.home');
// Auth Routes replaced Auth::routes(); to the following.... routes....
// Authentication Routes...
// Route::prefix('admin')->group(function () {
// $this->get('admin/login', 'Auth\LoginController@showLoginForm')->name('login');
// $this->post('admin/login', 'Auth\LoginController@login');
// $this->post('admin/logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// $this->get('admin/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// $this->post('admin/register', 'Auth\RegisterController@register');

// Password Reset Routes...
// $this->get('admin/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//  $this->post('admin/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// $this->get('admin/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// $this->post('admin/password/reset', 'Auth\ResetPasswordController@reset');
// });
//end of Auth Routes
//Stripe routes
Route::get('stripe', [StripeController::class, 'stripe']);
Route::post('stripe', [StripeController::class, 'stripePost'])->name('stripe.post');

//End of Stripe routes


