<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'status' => true,
        'user' => $request->user()
    ]);
});


Route::get('/', function (Request $request) {
    return response()->json([
        'status' => true,
        'message' => 'API is working'
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('cart/list', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/{id}', [CartController::class, 'removeCartItem'])->name('cart.remove');
Route::post('/cart/change_quantity', [CartController::class, 'changeQuantity']);

Route::post('/coupon-apply', [CartController::class, 'apply_coupon_code'])->name('coupon-apply');
Route::post('/coupon-remove', [CartController::class, 'remove_coupon_code'])->name('coupon-remove');

Route::post('/contact-submit', [FrontendController::class, 'submitContactForm'])->name('contact.submit');

Route::post('/newsletter-subscribe', [FrontendController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/collection-product', [FrontendController::class, 'collectionProduct'])->name('collectionProduct');
Route::get('/product/{slug}', [ProductController::class, 'productDetails'])->name('products.show');
Route::get('/search-suggestions', [ProductController::class, 'searchSuggestions'])->name('search.suggestions');

Route::get('/home', [FrontendController::class, 'home'])->name('home');
Route::get('/blogs', [FrontendController::class, 'blogs'])->name('blogs');
Route::get('/services', [FrontendController::class, 'services'])->name('services');

Route::get('/menu', [FrontendController::class, 'menu'])->name('menu');

Route::get('/pages/{page}', [FrontendController::class, 'page'])->name('page');
Route::get('/seo/{page}', [FrontendController::class, 'pageSEO'])->name('pageSEO');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.sendResetLink');
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.process');
Route::get('/order/{orderId?}', [OrderController::class, 'index'])->name('checkout.index');
Route::post('/cancel-order', [OrderController::class, 'cancelOrderRequest'])->name('checkout.cancelOrderRequest');
Route::post('/return-order', [OrderController::class, 'returnOrderRequest'])->name('checkout.returnOrderRequest');
