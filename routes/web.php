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
use App\Http\Controllers\Marketing\ContactsController;
use App\Http\Controllers\Marketing\ShopController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Marketing\LandingPageController;
use App\Http\Controllers\Marketing\LoggedInController;
use App\Models\Item;
use App\Models\Media;
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

Route::get('/cleanup-untracked-files', function(){
    $path = __DIR__.'/../public/images/thumbnails';
    $avatarPath = __DIR__.'/../public/images/thumbnails/100';
    $files = File::allFiles($path);
    $thumbnailsDeleted = 0;
    $avatarsDeleted = 0;
    if (is_array($files)) {
        foreach ($files as $file) {
            if ($file->isFile()) {
                // Check media table for the file. If the file does not exist delete
                $record = Media::where('thumbnail', 'LIKE', '%%'.$file->getFilename())->first();
                if(empty($record)) {
                    File::delete($path.'/'.$file->getFilename());
                    $thumbnailsDeleted++;

                    if (File::exists($avatarPath.'/'.$file->getFilename())) {
                        File::delete($avatarPath.'/'.$file->getFilename());
                        $avatarsDeleted++;
                    }
                }
            }
        }
    }

    $files = Storage::allFiles();
    $storageFilesDeleted = 0;
    if (is_array($files)) {
        foreach ($files as $file) {
            $record = Media::where('path', $file)->first();
            if(empty($record)) {
                Storage::delete($file);
                $storageFilesDeleted++;
            }
        }
    }

    printf('Deleted %d Storage Files, %d Thumbnail and %d Avatars', $storageFilesDeleted, $thumbnailsDeleted, $avatarsDeleted);
});

Route::get('/', function () {
    return Redirect::route('marketing.home');
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/al/{token}', [AutoLoginController::class, 'autoLogin'])->name('auto.login');

// Route::post('/broadcasting/auth', [SettingsController::class, 'authBroadcast']);
// Broadcast::routes(['middleware' => ['auth:api']]);

Route::get('/image/{id}', [MediaController::class, 'view'])->name('media.view');

Route::prefix('showroom')->group(function () {

    Route::get('/products', [ShopController::class, 'index'])->name('marketing.products');
    Route::get('/products/{id}', [ShopController::class, 'details'])->name('marketing.product.show');

    Route::get('/client/home', [CustomerDashboardController::class, 'home'])->name('customer.home');
   
    Route::middleware(['auth'])->group(function () {
        // Logged-in user routes
        Route::get('/home', [LoggedInController::class, 'loggedIn'])->name('customer.logged-in');
        
        // Customer Profile
        Route::get('/profile', [ProfileController::class, 'customerEdit'])->name('customer.profile.edit');
        Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('customer.profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('customer.password.update');

        Route::put('/client/update-email', [CustomerDashboardController::class, 'updateEmail'])->name('customer.update-email');

        Route::get('/client-order/create-order', [CustomerOrdersController::class, 'add'])->name('customer.new-order');
        Route::get('/client-order/my-orders', [CustomerOrdersController::class, 'index'])->name('customer.my-orders');
        Route::get('/client-order/{id}', [CustomerOrdersController::class, 'view'])->name('client.order.view');
        Route::get('/client-order/{id}/edit', [CustomerOrdersController::class, 'edit'])->name('client.order.edit');
        Route::put('/client-order/{id}/edit', [CustomerOrdersController::class, 'update'])->name('client.order.edit');
        Route::post('/client-order/orders', [CustomerDashboardController::class, 'records'])->name('customer.order-records');

        // Route::get('/client/home', [CustomerInvoiceController::class, 'home'])->name('customer.home');
        Route::get('/client-invoice/invoices', [CustomerInvoicesController::class, 'index'])->name('customer.invoices');
        Route::post('/client-invoice/invoices', [CustomerInvoicesController::class, 'records'])->name('customer.invoice-records');
        Route::get('/client-invoice/invoice/{id}', [CustomerInvoicesController::class, 'view'])->name('customer.invoice');
        Route::get('/client-invoice/receipt/{id}', [CustomerInvoicesController::class, 'receipt'])->name('customer.receipt');
        Route::post('/invoice/{id}/submit-bank-payment', [CustomerInvoicesController::class, 'submitBankPayment'])->name('customer.invoice.submit-bank-payment');
    });

});

Route::middleware(['auth'])->group(function () {
    Route::get('/panel/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/panel/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/panel/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');

    Route::get('/create-order', [OrdersController::class, 'add'])->name('order.add');
    Route::post('/create-order', [OrdersController::class, 'store'])->name('order.add');
    Route::get('/order/{id}', [OrdersController::class, 'view'])->name('order.view');
    Route::get('/order/{id}/edit', [OrdersController::class, 'edit'])->name('order.edit');
    Route::put('/order/{id}/edit', [OrdersController::class, 'update'])->name('order.edit');
    Route::put('/order/{id}/cancel', [OrdersController::class, 'cancel'])->name('order.cancel');
    Route::delete('/orders/delete', [OrdersController::class, 'delete'])->name('orders.delete');

    Route::post('/file/upload/{usage}', [FileUploadsController::class, 'uploadImage'])->name('file.upload');
    Route::get('/file/{path}/{type}', [FileUploadsController::class, 'get'])->name('file.load');
    Route::get('/file/download', [FileDownloadsController::class, 'download'])->name('file.download');

    // Item Categories Management
    Route::post('/customer/data', [CustomersController::class, 'findByMobile'])->name('customer.find');

    // Payment Providers
    Route::get('/payments/paystackk', [PaystackController::class, 'getConfig'])->name('paystack.config');

    // Session heartbeat to prevent 419 errors
    Route::get('/heartbeat', [\App\Http\Controllers\HeartbeatController::class, 'ping'])->name('heartbeat');

    
});



Route::prefix('ng')->group(function () {
    Route::get('/', [LandingPageController::class, 'index'])->name('marketing.home');

    Route::get('/about', function () {
        return view('about', [
            'title' => 'About Us',
            'description' => 'Learn more about our story and mission.',
            'page' => 'about',
        ]);
    })->name('about');

    Route::get('/contact', [ContactsController::class, 'index'])->name('contact');

    // Route::get('/products', function () {
    //     return view('marketing.products');
    // })->name('products');


    Route::get('/privacy', function () {
        return view('marketing.privacy', [
            'title' => 'Privacy Policy',
            'description' => 'Read our privacy policy to understand how we handle your data.',
            'page' => 'privacy',
        ]);
    })->name('privacy');

    // Route::get('/showroom', function () {
    //     return view('marketing.shop', [
    //         'title' => 'Shop Indigo',
    //         'description' => 'Explore our range of products available for purchase.',
    //         'page' => 'shop',
    //     ]);
    // })->name('shop');

    Route::get('/products/{id}', function ($id) {
        return view('marketing.product', ['id' => $id]);
    })->name('product.show');

    Route::get('/cart', function () {
        return view('marketing.cart');
    })->name('cart');

    Route::get('/signup', [App\Http\Controllers\CustomerRegistrationController::class, 'create'])->middleware('guest')->name('signup');
    Route::post('/signup', [App\Http\Controllers\CustomerRegistrationController::class, 'store'])->middleware('guest')->name('signup.store');

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
require __DIR__.'/admin.php';
