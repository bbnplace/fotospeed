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

Route::prefix('shop')->group(function () {

    Route::get('/products', [ShopController::class, 'index'])->name('marketing.products');
    Route::get('/products/{id}', [ShopController::class, 'details'])->name('marketing.product.show');

    Route::get('/client/home', [CustomerDashboardController::class, 'home'])->name('customer.home');
   
    Route::middleware(['auth'])->group(function () {
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

});



Route::prefix('ng')->group(function () {
    Route::get('/', function () {
        return view('marketing.home', [
            'title' => 'Welcome to Fotospeed',
            'description' => 'Capture your memories with our custom photo books.',
            'page' => 'home',
            'faqs' => [
                [
                    'question' => 'What is Fotospeed?',
                    'answer' => 'Fotospeed is a service that allows you to create custom photo books to preserve your memories.',
                ],
                [
                    'question' => 'How do I order a photo book?',
                    'answer' => 'You can place an order online, in-person at one of our branches, or by contacting our customer service team.',
                ],
                [
                    'question' => 'What payment methods do you accept?',
                    'answer' => 'We accept various payment methods, including cash, and online payment.',
                ],
                [
                    'question' => 'What are your business hours?',
                    'answer' => 'Our business hours are Monday to Friday from 9 AM to 5 PM',
                ],
                [
                    'question' => 'What is your delivery policy?',
                    'answer' => 'We offer delivery services within Lagos and nationwide.',
                ],
            ],
            'team' => [
                [
                    'id' => 1,
                    'name' => 'Antony Issac',
                    'role' => 'Founder & CEO',
                    'image' => 'letest-team-img2.jpg',
                    'socials' => [
                        'facebook' => 'https://www.facebook.com/antony.issac',
                        'twitter' => 'https://twitter.com/antony_issac',
                        'instagram' => 'https://www.instagram.com/antony_issac',
                    ],
                ],
                [
                    'id' => 2,
                    'name' => 'Jane Doe',
                    'role' => 'Creative Director',
                    'image' => 'letest-team-img2.jpg',
                    'socials' => [
                        'facebook' => 'https://www.facebook.com/jane.doe',
                        'twitter' => 'https://twitter.com/jane_doe',
                        'instagram' => 'https://www.instagram.com/jane_doe',
                    ],
                ],
                [
                    'id' => 3,
                    'name' => 'John Smith',
                    'role' => 'Marketing Manager',
                    'image' => 'letest-team-img2.jpg',
                    'socials' => [
                        'facebook' => 'https://www.facebook.com/john.smith',
                        'twitter' => 'https://twitter.com/john_smith',
                        'instagram' => 'https://www.instagram.com/john_smith',
                    ],
                ],
            ],
            'testimonials' => [
                [
                    'name' => 'Alice Johnson',
                    'role' => 'Photographer',
                    'organization' => 'Organization Name',
                    'feedback' => 'Fotospeed helped me create a beautiful album for my wedding. Highly recommend!',
                    'image' => '01.png',
                ],
                [
                    'name' => 'Bob Brown',
                    'role' => 'CEO',
                    'organization' => 'Agency Name',
                    'feedback' => 'Great service and quality! My family loved the photo book I created.',
                    'image' => '01.png',
                ],
                [
                    'name' => 'Charlie Davis',
                    'role' => 'Customer',
                    'organization' => '',
                    'feedback' => 'Fast delivery and excellent customer support. Will order again!',
                    'image' => '01.png',
                ],
            ],
            'kpis' => [
                'total_orders' => 36200,
                'happy_customers' => 1200,
                'branches' => 6,
                'team_members' => 20,
                'experience' => 11,
                'projects_completed' => 300,
            ],
            'features' => [
                [
                    'title' => 'High Quality Prints',
                    'description' => 'We use the best printing technology to ensure your photos look stunning.',
                    'icon' => 'feature-img1.svg',
                    'link' => '',
                ],
                [
                    'title' => 'Nation-wide Delivery',
                    'description' => 'Get your photo books delivered nationwide quickly and safely.',
                    'icon' => 'service3.svg',
                    'link' => '',
                ],
                [
                    'title' => 'Best Online Support',
                    'description' => 'Available from 9am - 5pm Mon - Sat to assist you on call and on Whatsapp.',
                    'icon' => 'feature-img3.svg',
                    'link' => '',
                ],
            ],
        ]);
    })->name('marketing.home');

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

    Route::get('/shop', function () {
        return view('marketing.shop', [
            'title' => 'Shop Indigo',
            'description' => 'Explore our range of products available for purchase.',
            'page' => 'shop',
        ]);
    })->name('shop');

    Route::get('/products/{id}', function ($id) {
        return view('marketing.product', ['id' => $id]);
    })->name('product.show');

    Route::get('/cart', function () {
        return view('marketing.cart');
    })->name('cart');

    Route::get('/signup', function () {
        return view('marketing.signup', [
            'title' => 'Sign Up',
            'description' => 'Create an account to get started with Fotospeed.',
            'page' => 'signup',
        ]);
    })->middleware('guest')->name('signup');

    Route::post('/signup', function () {
        // Handle signup logic here
        return redirect()->route('home')->with('success', 'Signup successful!');
    })->middleware('guest')->name('signup');

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
