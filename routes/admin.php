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
use App\Http\Controllers\UserRegistrationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'indigo-team'])->group(function (){

    // Orders Management Module
    Route::get('panel/orders', [OrdersController::class, 'index'])->name('orders');
    Route::post('panel/orders', [OrdersController::class, 'records'])->name('order.records');

    // Customers Management Module
    Route::get('panel/customers', [CustomersController::class, 'index'])->name('customers');
    Route::post('panel/customers', [CustomersController::class, 'records'])->name('customers.records');
    Route::get('panel/customer/add', [CustomersController::class, 'add'])->name('customer.add');
    Route::get('panel/customer/{id}/edit', [CustomersController::class, 'edit'])->name('customer.edit');
    Route::put('panel/customer/{id}/edit', [CustomersController::class, 'update'])->name('customer.edit');
    Route::get('panel/customer/{id}', [CustomersController::class, 'view'])->name('customer.view');
    Route::delete('panel/customers/delete', [CustomersController::class, 'delete'])->name('customers.delete');


    // Staff Management Module
    Route::get('panel/staff', [StaffController::class, 'index'])->name('staff');
    Route::post('panel/staff', [StaffController::class, 'records'])->name('staff.records');
    Route::get('panel/staff/add', [StaffController::class, 'add'])->name('staff.add');
    Route::get('panel/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('panel/staff/{id}/edit', [StaffController::class, 'update'])->name('staff.edit');
    Route::get('panel/staff/{id}', [StaffController::class, 'view'])->name('staff.view');
    Route::delete('panel/staff/delete', [StaffController::class, 'delete'])->name('staff.delete');


    // Messaging Module
    Route::get('panel/messages', [MessagesController::class, 'index'])->name('messages');
    Route::post('panel/messages', [MessagesController::class, 'records'])->name('messages.records');
    Route::get('panel/write-messages', [MessagesController::class, 'write'])->name('message.write');
    Route::delete('panel/messages/delete', [MessagesController::class, 'delete'])->name('messages.delete');


    // Groups Management Module
    Route::get('panel/groups', [GroupsController::class, 'index'])->name('groups');
    Route::post('panel/groups', [GroupsController::class, 'records'])->name('group.records');
    Route::get('panel/group/add', [GroupsController::class, 'add'])->name('group.add');
    Route::post('panel/group/add', [GroupsController::class, 'store'])->name('group.add');
    Route::get('panel/group/{id}/edit', [GroupsController::class, 'edit'])->name('group.edit');
    Route::put('panel/group/{id}/edit', [GroupsController::class, 'update'])->name('group.edit');
    Route::get('panel/group/{id}', [GroupsController::class, 'view'])->name('group.view');
    Route::delete('panel/groups/delete', [GroupsController::class, 'delete'])->name('groups.delete');

    // SMS Template Management Module
    Route::get('panel/sms-templates', [SmsTemplatesController::class, 'index'])->name('sms-templates');
    Route::post('panel/sms-templates', [SmsTemplatesController::class, 'records'])->name('sms-templates.records');
    Route::get('panel/sms-template/add', [SmsTemplatesController::class, 'add'])->name('sms-template.add');
    Route::post('panel/sms-template/add', [SmsTemplatesController::class, 'store'])->name('sms-template.add');
    Route::get('panel/sms-template/{id}/edit', [SmsTemplatesController::class, 'edit'])->name('sms-template.edit');
    Route::put('panel/sms-template/{id}/edit', [SmsTemplatesController::class, 'update'])->name('sms-template.edit');
    Route::get('panel/sms-template/{id}', [SmsTemplatesController::class, 'view'])->name('sms-template.view');
    Route::delete('panel/sms-templates/delete', [SmsTemplatesController::class, 'delete'])->name('sms-templates.delete');

    // Email Template Management Module
    Route::get('panel/email-templates', [EmailTemplatesController::class, 'index'])->name('email-templates');
    Route::post('panel/email-templates', [EmailTemplatesController::class, 'records'])->name('email-templates.records');
    Route::get('panel/email-template/add', [EmailTemplatesController::class, 'add'])->name('email-template.add');
    Route::post('panel/email-template/add', [EmailTemplatesController::class, 'store'])->name('email-template.add');
    Route::get('panel/email-template/{id}/edit', [EmailTemplatesController::class, 'edit'])->name('email-template.edit');
    Route::put('panel/email-template/{id}/edit', [EmailTemplatesController::class, 'update'])->name('email-template.edit');
    Route::get('panel/email-template/{id}', [EmailTemplatesController::class, 'view'])->name('email-template.view');
    Route::delete('panel/email-templates/delete', [EmailTemplatesController::class, 'delete'])->name('email-templates.delete');



    // Branch Management Module
    Route::get('panel/states', [StatesController::class, 'index'])->name('states');
    Route::post('panel/states', [StatesController::class, 'records'])->name('state.records');
    Route::get('panel/state/add', [StatesController::class, 'add'])->name('state.add');
    Route::post('panel/state/add', [StatesController::class, 'store'])->name('state.store');
    Route::get('panel/state/{id}/edit', [StatesController::class, 'edit'])->name('state.edit');
    Route::put('panel/state/{id}/edit', [StatesController::class, 'update'])->name('state.edit');
    Route::get('panel/state/{id}', [StatesController::class, 'view'])->name('state.view');
    Route::delete('panel/states/delete', [StatesController::class, 'delete'])->name('states.delete');

    // Branch Management Module
    Route::get('panel/branches', [BranchesController::class, 'index'])->name('branches');
    Route::post('panel/branches', [BranchesController::class, 'records'])->name('branches.records');
    Route::get('panel/branch/add', [BranchesController::class, 'add'])->name('branch.add');
    Route::post('panel/branch/add', [BranchesController::class, 'store'])->name('branch.store');
    Route::get('panel/branch/{id}/edit', [BranchesController::class, 'edit'])->name('branch.edit');
    Route::put('panel/branch/{id}/edit', [BranchesController::class, 'update'])->name('branch.edit');
    Route::get('panel/branch/{id}/detail', [BranchesController::class, 'view'])->name('branch.view');
    Route::delete('panel/branches/delete', [BranchesController::class, 'delete'])->name('branches.delete');

    // Categories Management Module
    Route::get('panel/categories', [CategoriesController::class, 'index'])->name('categories');
    Route::post('panel/categories', [CategoriesController::class, 'records'])->name('categories.records');
    Route::get('panel/category/add', [CategoriesController::class, 'add'])->name('category.add');
    Route::post('panel/category/add', [CategoriesController::class, 'store'])->name('category.store');
    Route::get('panel/category/{ref}/edit', [CategoriesController::class, 'edit'])->name('category.edit');
    Route::put('panel/category/{ref}/edit', [CategoriesController::class, 'update'])->name('category.edit');
    Route::get('panel/category/{id}', [CategoriesController::class, 'view'])->name('category.view');
    Route::delete('panel/categories/delete', [CategoriesController::class, 'delete'])->name('categories.delete');

    // Items Management Module
    Route::get('panel/items', [ItemsController::class, 'index'])->name('items');
    Route::post('panel/items', [ItemsController::class, 'records'])->name('items.records');
    Route::get('panel/item/add', [ItemsController::class, 'add'])->name('item.add');
    Route::post('panel/item/add', [ItemsController::class, 'store'])->name('item.store');
    Route::get('panel/item/{id}/edit', [ItemsController::class, 'edit'])->name('item.edit');
    Route::put('panel/item/{id}/edit', [ItemsController::class, 'update'])->name('item.edit');
    Route::get('panel/item/{id}', [ItemsController::class, 'view'])->name('item.view');
    Route::delete('panel/items/delete', [ItemsController::class, 'delete'])->name('items.delete');

    Route::post('register', [UserRegistrationController::class, 'register'])->name('user.register');
});

