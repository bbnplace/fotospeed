<?php


use App\Http\Controllers\Marketing\ContactsController;
use App\Http\Controllers\Marketing\ShopController;
use App\Http\Controllers\Marketing\LandingPageController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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



Route::get('/', function () {
    return Redirect::route('marketing.home');
});


Route::prefix('showroom')->group(function () {
    Route::get('/products', [ShopController::class, 'index'])->name('marketing.products');
    Route::get('/products/{id}', [ShopController::class, 'details'])->name('marketing.product.show');
    
});


Route::prefix('storefront')->group(function () {
    Route::get('/', [LandingPageController::class, 'index'])->name('marketing.home');

    Route::get('/about', function () {
        return view('about', [
            'title' => 'About Us',
            'description' => 'Learn more about our story and mission.',
            'page' => 'about',
        ]);
    })->name('about');

    Route::get('/contact', [ContactsController::class, 'index'])->name('contact');


    Route::get('/privacy', function () {
        return view('marketing.privacy', [
            'title' => 'Privacy Policy',
            'description' => 'Read our privacy policy to understand how we handle your data.',
            'page' => 'privacy',
        ]);
    })->name('privacy');


    Route::get('/products/{id}', function ($id) {
        return view('marketing.product', ['id' => $id]);
    })->name('product.show');

    Route::get('/cart', function () {
        return view('marketing.cart');
    })->name('cart');


    Route::get('/signin', function () {
        return view('marketing.signin', [
            'title' => 'Sign In',
            'description' => 'Access your account by signing in.',
            'page' => 'signin',
        ]);
    })->middleware('guest')->name('signin');

    Route::get('/team-detail', function () {
        return view('marketing.team-detail', [
            'title' => 'Team Detail',
            'description' => 'Learn more about our team members.',
            'page' => 'team-detail',
        ]);
    })->name('team.details');

});


require __DIR__.'/auth.php';
// Admin routes are now provided by Cecula Flow package
