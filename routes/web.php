<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index')->name('home.index');
Route::post('/susbcribe', 'NewsletterController@add')->name('newsletter.add');

Route::get('/dashboard', 'AdminController@index')->name('admin.index')->middleware(['auth','admin']);
Route::patch('/dashboard', 'AdminController@updatereminder')->name('admin.reminder')->middleware(['auth','admin']);

Route::get('/order', 'AdminController@order')->name('admin.order')->middleware(['auth','admin']);
Route::get('/order/{id}', 'AdminController@show_order')->name('admin.showorder')->middleware(['auth','admin']);

Route::get('/user', 'AdminController@user')->name('admin.user')->middleware(['auth','admin']);

Route::get('/admin-category', 'CategoryController@index')->name('admin.category')->middleware(['auth','admin']);
Route::get('/admin-category/add', 'CategoryController@form')->name('admin.category.form')->middleware(['auth','admin']);
Route::post('/admin-category/create', 'CategoryController@create')->name('admin.category.create')->middleware(['auth','admin']);

Route::get('/admin-category/edit/{id}', 'CategoryController@editform')->name('admin.category.edit.form')->middleware(['auth','admin']);

Route::patch('/admin-category/edit/{id}', 'CategoryController@edit')->name('admin.category.edit')->middleware(['auth','admin']);
Route::get('/admin-category/remove/{id}', 'CategoryController@remove')->name('admin.category.remove')->middleware(['auth','admin']);


Route::get('/admin-variant', 'VariantController@index')->name('admin.variant')->middleware(['auth','admin']);
Route::get('/admin-variant/add', 'VariantController@form')->name('admin.variant.form')->middleware(['auth','admin']);
Route::post('/admin-variant/create', 'VariantController@create')->name('admin.variant.create')->middleware(['auth','admin']);

Route::get('/admin-variant/edit/{id}', 'VariantController@editform')->name('admin.variant.edit.form')->middleware(['auth','admin']);


Route::patch('/admin-variant/edit/{id}', 'VariantController@edit')->name('admin.variant.edit')->middleware(['auth','admin']);

Route::get('/admin-variant/remove/{id}', 'VariantController@remove')->name('admin.variant.remove')->middleware(['auth','admin']);


Route::get('/admin-product', 'ProductController@list')->name('admin.product')->middleware(['auth','admin']);
Route::get('/admin-product/add', 'ProductController@form')->name('admin.addform')->middleware(['auth','admin']);
Route::post('/admin-product/add', 'ProductController@create')->name('product.create')->middleware(['auth','admin']);
Route::get('/admin-product/edit/{id}', 'ProductController@editform')->name('product.editform')->middleware(['auth','admin']);
Route::patch('/admin-product/edit/{id}', 'ProductController@edit')->name('product.edit')->middleware(['auth','admin']);
Route::get('/admin-product/remove/{id}', 'ProductController@remove')->name('product.remove')->middleware(['auth','admin']);

Route::get('/admin-stock/{id}', 'StockController@index')->name('admin.stock')->middleware(['auth','admin']);

Route::get('/admin-stock/show', 'StockController@show')->name('admin.stockshow')->middleware(['auth','admin']);
Route::get('/admin-stock/remove/{id}/{stock}', 'StockController@remove')->name('admin.removestock')->middleware(['auth','admin']);
Route::get('/admin-stock/edit/{id}', 'StockController@editform')->name('admin.editform')->middleware(['auth','admin']);
Route::patch('/admin-stock/edit/{id}', 'StockController@editstock')->name('admin.editstock')->middleware(['auth','admin']);

Route::get('/admin-stock/add/{id}', 'StockController@addform')->name('admin.addstockform')->middleware(['auth','admin']);
Route::post('/admin-stock/add/{id}', 'StockController@addstock')->name('admin.addstock')->middleware(['auth','admin']);
Route::get('/admin-stock/add-group/{id}', 'StockController@addformgroup')->name('admin.addstockformgroup')->middleware(['auth','admin']);

Route::get('/admin-stock/add-variant/{id}', 'StockController@addformvariant')->name('admin.addstockformvariant')->middleware(['auth','admin']);

Route::post('/admin-stock/add-group/{id}', 'StockController@addstockgroup')->name('admin.addstockgroup')->middleware(['auth','admin']);

Route::post('/admin-stock/add-variant/{id}', 'StockController@addstockvariant')->name('admin.addstockvariant')->middleware(['auth','admin']);

Route::post('/admin-stock/save/{id}', 'StockController@savestock')->name('admin.savestock')->middleware(['auth','admin']);


Route::get('/productos','ProductController@index')->name('product.index');
Route::get('/productos/{category}','ProductController@index')->name('product.index2');
Route::get('/productos/{category}/{subcategory}','ProductController@index')->name('product.index3');

/*
Route::get('/product/{i}','ProductController@index')->name('product.show');

Route::get('/product/filter','ProductController@filter')->name('product.filter');
*/
Route::get('/producto/{id}-{slug}','ProductController@show')->name('product.show');

Route::get('/cart/destroy','CartController@destroy')->name('cart.test');

Route::get('/cart','CartController@index')->name('cart.index');

Route::get('/cart/view','CartController@viewCart')->name('cart.view');
Route::get('/cart/add','CartController@add')->name('cart.add');
Route::get('/cart/add/item','CartController@addItem')->name('cart.addItem');
Route::get('/cart/remove','CartController@remove')->name('cart.remove');

Route::get('/checkout','CheckoutController@index')->name('checkout.index')->middleware('auth');
Route::post('/checkout','CheckoutController@checkout')->name('checkout')->middleware('auth');
Route::any('/checkout/payment','CheckoutController@payment')->name('checkout.payment');
Route::any('/checkout/notification','CheckoutController@notification')->name('checkout.notification');


Route::get('/user/order','OrderController@show')->name('order.show')->middleware('auth');

Route::get('/profile/{user}/edit','ProfileController@edit')->name('profile.edit')->middleware('auth');

Route::patch('/profile/{user}','ProfileController@update')->name('profile.update')->middleware('auth');

Auth::routes();
