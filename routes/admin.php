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
use App\Http\Controllers\StatesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'indigo-team'])->group(function (){

    // Orders Management Module
    Route::get('/panel/orders', [OrdersController::class, 'index'])->name('orders');

    // Customers Management Module
    Route::get('/panel/customers', [CustomersController::class, 'index'])->name('customers');
    Route::get('panel/customer/add', [CustomersController::class, 'add'])->name('customer.add');
    Route::get('panel/customer/{id}/edit', [CustomersController::class, 'edit'])->name('customer.edit');
    Route::get('panel/customer/{id}', [CustomersController::class, 'view'])->name('customer.view');

    // Messaging Module
    Route::get('/panel/messages', [MessagesController::class, 'index'])->name('messages');


    // Groups Management Module
    Route::get('/panel/groups', [GroupsController::class, 'index'])->name('groups');
    Route::get('panel/group/add', [GroupsController::class, 'add'])->name('group.add');
    Route::get('panel/group/{id}/edit', [GroupsController::class, 'edit'])->name('group.edit');
    Route::get('panel/group/{id}', [GroupsController::class, 'view'])->name('group.view');

    // SMS Template Management Module
    Route::get('/panel/sms-templates', [SmsTemplatesController::class, 'index'])->name('sms-templates');

    // Email Template Management Module
    Route::get('/panel/email-templates', [EmailTemplatesController::class, 'index'])->name('email-templates');


    // Staff Management Module
    Route::get('/panel/staff', [StaffController::class, 'index'])->name('staff');
    Route::post('panel/staff', [StaffController::class, 'records'])->name('staff.records');
    Route::get('panel/staff/add', [StaffController::class, 'add'])->name('staff.add');
    Route::post('panel/staff/add', [StaffController::class, 'store'])->name('staff.store');
    Route::get('panel/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::get('panel/staff/{id}', [StaffController::class, 'view'])->name('staff.view');

    // Branch Management Module
    Route::get('/panel/states', [StatesController::class, 'index'])->name('states');
    Route::post('panel/states', [StatesController::class, 'records'])->name('state.records');
    Route::get('panel/state/add', [StatesController::class, 'add'])->name('state.add');
    Route::post('panel/state/add', [StatesController::class, 'store'])->name('state.store');
    Route::get('panel/state/{id}/edit', [StatesController::class, 'edit'])->name('state.edit');
    Route::get('panel/state/{id}', [StatesController::class, 'view'])->name('state.view');

    // Branch Management Module
    Route::get('/panel/branches', [BranchesController::class, 'index'])->name('branches');
    Route::post('panel/branches', [BranchesController::class, 'records'])->name('branches.records');
    Route::get('panel/branch/add', [BranchesController::class, 'add'])->name('branch.add');
    Route::post('panel/branch/add', [BranchesController::class, 'store'])->name('branch.store');
    Route::get('panel/branch/{id}/edit', [BranchesController::class, 'edit'])->name('branch.edit');
    Route::get('panel/branch/{id}', [BranchesController::class, 'view'])->name('branch.view');

    // Branches Management Module
    Route::get('/panel/categories', [CategoriesController::class, 'index'])->name('categories');
    Route::post('/panel/categories', [CategoriesController::class, 'records'])->name('categories.records');
    Route::get('/panel/category/add', [CategoriesController::class, 'add'])->name('category.add');
    Route::post('/panel/category/add', [CategoriesController::class, 'store'])->name('category.store');
    Route::get('/panel/category/{ref}/edit', [CategoriesController::class, 'edit'])->name('category.edit');
    Route::put('/panel/category/{ref}/edit', [CategoriesController::class, 'edit'])->name('category.update');

    // Items Management Module
    Route::get('/panel/items', [ItemsController::class, 'index'])->name('items');
    Route::get('panel/item/add', [ItemsController::class, 'add'])->name('item.add');
    Route::get('panel/item/{id}/edit', [ItemsController::class, 'edit'])->name('item.edit');
    Route::get('panel/item/{id}', [ItemsController::class, 'view'])->name('item.view');
});

