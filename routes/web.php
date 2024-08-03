<?php

use App\Http\Controllers\Auth\AutoLoginController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerInvoicesController;
use App\Http\Controllers\CustomerOrdersController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\FileUploadsController;
use App\Http\Controllers\FileDownloadsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\ProfileController;
use App\Models\Item;
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

Route::get('/test', function(){
    // if (extension_loaded('gd')) {
    //     echo "GD extension is installed.";
    // } else {
    //     echo "GD extension is not installed.";
    // 
    // }
    return redirect(route('login'));
});

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    return Redirect::route('login');
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/al/{token}', [AutoLoginController::class, 'autoLogin'])->name('auto.login');

Route::post('/broadcasting/auth', [SettingsController::class, 'authBroadcast']);


Route::middleware('auth')->group(function () {
    Route::get('/panel/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/panel/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/panel/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');

    Route::get('/create-order', [OrdersController::class, 'add'])->name('order.add');
    Route::post('/create-order', [OrdersController::class, 'store'])->name('order.add');
    Route::get('/order/{id}', [OrdersController::class, 'view'])->name('order.view');
    Route::get('/order/{id}/edit', [OrdersController::class, 'edit'])->name('order.edit');
    Route::put('/order/{id}/edit', [OrdersController::class, 'update'])->name('order.edit');
    Route::put('/order/{id}/cancel', [OrdersController::class, 'cancel'])->name('order.cancel');
    Route::delete('/orders/delete', [OrdersController::class, 'delete'])->name('orders.delete');

    Route::post('/file/upload', [FileUploadsController::class, 'uploadImage'])->name('file.upload');
    Route::get('/file/{path}/{type}', [FileUploadsController::class, 'get'])->name('file.load');
    Route::get('/file/download', [FileDownloadsController::class, 'download'])->name('file.download');

    // Item Categories Management
    Route::post('/customer/data', [CustomersController::class, 'findByMobile'])->name('customer.find');

    Route::get('/client/home', [CustomerDashboardController::class, 'home'])->name('customer.home');
    Route::get('/client-order/create-order', [CustomerOrdersController::class, 'add'])->name('customer.new-order');
    Route::get('/client-order/my-orders', [CustomerOrdersController::class, 'index'])->name('customer.my-orders');
    Route::get('/client-order/{id}', [CustomerOrdersController::class, 'view'])->name('client.order.view');
    Route::get('/client-order/{id}/edit', [CustomerOrdersController::class, 'edit'])->name('client.order.edit');
    Route::put('/client-order/{id}/edit', [CustomerOrdersController::class, 'update'])->name('client.order.edit');
    Route::post('/client-order/orders', [CustomerDashboardController::class, 'records'])->name('customer.order-records');

    // Customer Invoice
    // Route::get('/client/home', [CustomerInvoiceController::class, 'home'])->name('customer.home');
    Route::get('/client-invoice/invoices', [CustomerInvoicesController::class, 'index'])->name('customer.invoices');
    Route::post('/client-invoice/invoices', [CustomerInvoicesController::class, 'records'])->name('customer.invoice-records');
    Route::get('/client-invoice/invoice/{id}', [CustomerInvoicesController::class, 'view'])->name('customer.invoice');
    Route::get('/client-invoice/receipt/{id}', [CustomerInvoicesController::class, 'receipt'])->name('customer.receipt');

    // Payment Providers
    Route::get('/payments/paystackk', [PaystackController::class, 'getConfig'])->name('paystack.config');
});


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
