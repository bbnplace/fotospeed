<?php

use App\Http\Controllers\BranchesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\EmailTemplatesController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\SmsTemplatesController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'indigo-team'])->group(function (){

    // Orders Management Module
    Route::get('/panel/orders', [OrdersController::class, 'index'])->name('orders');

    // Customers Management Module
    Route::get('/panel/customers', [CustomersController::class, 'index'])->name('customers');

    // Messaging Module
    Route::get('/panel/messages', [MessagesController::class, 'index'])->name('messages');


    // Groups Management Module
    Route::get('/panel/groups', [GroupsController::class, 'index'])->name('groups');

    // SMS Template Management Module
    Route::get('/panel/sms-templates', [SmsTemplatesController::class, 'index'])->name('sms-templates');

    // Email Template Management Module
    Route::get('/panel/email-templates', [EmailTemplatesController::class, 'index'])->name('email-templates');


    // Staff Management Module
    Route::get('/panel/staff', [StaffController::class, 'index'])->name('staff');

    // Branch Management Module
    Route::get('/panel/branches', [BranchesController::class, 'index'])->name('branches');

    // Branches Management Module
    Route::get('/panel/categories', [CategoriesController::class, 'index'])->name('categories');

    // Items Management Module
    Route::get('/panel/items', [ItemsController::class, 'index'])->name('items');
});

