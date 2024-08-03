<?php

use App\Http\Controllers\CommunicationLogController;
use App\Http\Controllers\CustomerFeedbacksController;
use App\Http\Controllers\FileDownloadsController;
use App\Http\Controllers\Messaging\A2PSmsController;
use App\Http\Controllers\Messaging\EmailsController;
use App\Http\Controllers\Messaging\HostedSimController;
use App\Http\Controllers\Messaging\WhatsappController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\OrderTasksController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailTemplatesController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\JobProcessTransferController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProcessesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SmsTemplatesController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StatesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WhatsappTemplatesController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'team.console'])->group(function (){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders Management Module
    Route::get('panel/orders', [OrdersController::class, 'index'])->name('orders');
    Route::post('panel/orders', [OrdersController::class, 'records'])->name('order.records');

    // Customer Management
    Route::get('panel/customers', [CustomersController::class, 'index'])->name('customers');
    Route::get('panel/customer/add', [CustomersController::class, 'add'])->name('customer.add');
    Route::get('panel/customer/{id}', [CustomersController::class, 'view'])->name('customer.view');
    Route::post('panel/find-customer', [CustomersController::class, 'findCustomerByMobileOrName'])->name('customer.search');


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

    // Invoice Routes
    Route::get('panel/invoices', [InvoicesController::class, 'index'])->name('invoices');
    Route::post('panel/invoices', [InvoicesController::class, 'records'])->name('invoice.records');
    Route::get('/panel/invoice/{id}', [InvoicesController::class, 'view'])->name('invoice');
    Route::post('panel/invoice/create', [InvoicesController::class, 'create'])->name('invoice.create');


    Route::post('register', [UserRegistrationController::class, 'register'])->name('user.register');

    Route::get('panel/restricted', [PermissionsController::class, 'restrictionNotice'])->name('dashboard.restricted');

    // Route for forwarding an order to the next process
    Route::post('panel/forward', [JobProcessTransferController::class, 'forward'])->name('process.forward');
    Route::get('panel/order/{id}/task-completed', [JobProcessTransferController::class, 'completed'])->name('process.completed');

    Route::get('/report/{type}', [DashboardController::class, 'report'])->name('report');
    Route::get('/report/{type}/json', [DashboardController::class, 'getReports'])->name('report.json');
    Route::get('/report', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard', [DashboardController::class, 'home'])->name('dashboard');
    Route::post('/report/export/file', [DashboardController::class, 'export'])->name('report.export');

    // Task Routes
    Route::get('/tasks/unassigned', [TasksController::class,'loadUnassignedTeamTasks'])->name('tasks.team');
    Route::get('/tasks/unassigned/{orderId}', [TasksController::class,'loadUnassignedOrderTasks'])->name('tasks.order.unassigned');
    
    Route::get('/tasks/accepted', [TasksController::class,'loadTasks'])->name('tasks.usertasks');
    Route::post('/task/pick', [TasksController::class,'pickTask'])->name('task.pick');
    Route::post('/task/update', [TasksController::class,'updateTasks'])->name('task.update');

    // Order Communication Log
    Route::get('/order-log/{orderId}', [CommunicationLogController::class, 'index'])->name('order.log');
    Route::post('/order-log/write', [CommunicationLogController::class, 'store'])->name('order.log.write');

    // Customer Feedback Log
    Route::get('/customer-feedback-log/{customerId}', [CustomerFeedbacksController::class, 'index'])->name('customer.feedback');
    Route::post('/customer-feedback-log/write', [CustomerFeedbacksController::class, 'store'])->name('customer.feedback.write');

    // Whatsapp Message
    Route::get('/message/whatsapp/{customerId}', [WhatsappController::class, 'index'])->name('customer.whatsapp.log');
    Route::post('/message/whatsapp/write', [WhatsappController::class, 'store'])->name('customer.whatsapp.write');

    // Hosted SIM Message
    Route::get('/message/hostedsim/{customerId}', [HostedSimController::class, 'index'])->name('customer.hostedsim.log');
    Route::post('/message/hostedsim/write', [HostedSimController::class, 'store'])->name('customer.hostedsim.write');

    // eMail Message
    Route::get('/message/email/{customerId}', [EmailsController::class, 'index'])->name('customer.email.log');
    Route::post('/message/email/write', [EmailsController::class, 'store'])->name('customer.email.write');

    // A2P SMS Message
    Route::get('/message/a2psms/{customerId}', [A2PSmsController::class, 'index'])->name('customer.a2psms.log');
    Route::post('/message/a2psms/write', [A2PSmsController::class, 'store'])->name('customer.a2psms.write');

    // This Task will be open for Admin and Management
    Route::get('/tasks/order/task/{orderId}', [OrderTasksController::class,'index'])->name('tasks.order.dashboard');
    Route::get('/tasks/order/{orderId}', [TasksController::class,'loadOrderTasks'])->name('tasks.order');

    // Media Download
    Route::get('/file/download', [FileDownloadsController::class, 'fetch'])->name('file.fetch');

    // Notifications
    Route::get('/recent-notifications', [NotificationsController::class,'recent'])->name('notifications.recent');
});


Route::middleware(['auth', 'admin.only'])->group(function (){

    // Items Management Module
    Route::get('panel/items', [ItemsController::class, 'index'])->name('items');
    Route::post('panel/items', [ItemsController::class, 'records'])->name('items.records');
    Route::get('panel/item/add', [ItemsController::class, 'add'])->name('item.add');
    Route::post('panel/item/add', [ItemsController::class, 'store'])->name('item.store');
    Route::get('panel/item/{id}/edit', [ItemsController::class, 'edit'])->name('item.edit');
    Route::put('panel/item/{id}/edit', [ItemsController::class, 'update'])->name('item.edit');
    Route::get('panel/item/{id}', [ItemsController::class, 'view'])->name('item.view');
    Route::delete('panel/items/delete', [ItemsController::class, 'delete'])->name('items.delete');
    Route::put('panel/item-process/{id}/save', [ItemsController::class,'saveProcessData'])->name('item.saveprocess');

    // Customers Management Module
    Route::post('panel/customers', [CustomersController::class, 'records'])->name('customers.records');
    Route::get('panel/customer/{id}/edit', [CustomersController::class, 'edit'])->name('customer.edit');
    Route::put('panel/customer/{id}/edit', [CustomersController::class, 'update'])->name('customer.edit');
    Route::delete('panel/customers/delete', [CustomersController::class, 'delete'])->name('customers.delete');

    // Staff Management Module
    Route::get('panel/staff', [StaffController::class, 'index'])->name('staff');
    Route::post('panel/staff', [StaffController::class, 'records'])->name('staff.records');
    Route::get('panel/staff/add', [StaffController::class, 'add'])->name('staff.add');
    Route::post('panel/staff/add', [StaffController::class, 'store'])->name('staff.add');
    Route::get('panel/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('panel/staff/{id}/edit', [StaffController::class, 'update'])->name('staff.edit');
    Route::get('panel/staff/{id}', [StaffController::class, 'view'])->name('staff.view');
    Route::delete('panel/staff/delete', [StaffController::class, 'delete'])->name('staff.delete');

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

    // Processes Management Module
    Route::get('panel/processes', [ProcessesController::class, 'index'])->name('processes');
    Route::post('panel/processes', [ProcessesController::class, 'records'])->name('processes.records');
    Route::get('panel/process/add', [ProcessesController::class, 'add'])->name('process.add');
    Route::post('panel/process/add', [ProcessesController::class, 'store'])->name('process.store');
    Route::get('panel/process/{id}/edit', [ProcessesController::class, 'edit'])->name('process.edit');
    Route::put('panel/process/{id}/edit', [ProcessesController::class, 'update'])->name('process.edit');
    Route::get('panel/process/{id}', [ProcessesController::class, 'view'])->name('process.view');
    Route::delete('panel/processes/delete', [ProcessesController::class, 'delete'])->name('processes.delete');

    // Roles Management
    Route::get('panel/roles', [RolesController::class, 'index'])->name('roles');
    Route::post('panel/roles', [RolesController::class, 'records'])->name('roles.records');
    Route::get('panel/role/add', [RolesController::class, 'add'])->name('role.add');
    Route::post('panel/role/add', [RolesController::class, 'store'])->name('role.store');
    Route::get('panel/role/{id}/edit', [RolesController::class, 'edit'])->name('role.edit');
    Route::put('panel/role/{id}/edit', [RolesController::class, 'update'])->name('role.edit');
    Route::get('panel/role/{id}', [RolesController::class, 'view'])->name('role.view');
    Route::delete('panel/roles/delete', [RolesController::class, 'delete'])->name('roles.delete');

    // SMS Template Management Module
    Route::get('panel/sms-templates', [SmsTemplatesController::class, 'index'])->name('sms-templates');
    Route::post('panel/sms-templates', [SmsTemplatesController::class, 'records'])->name('sms-templates.records');
    Route::get('panel/sms-template/add', [SmsTemplatesController::class, 'add'])->name('sms-template.add');
    Route::post('panel/sms-template/add', [SmsTemplatesController::class, 'store'])->name('sms-template.add');
    Route::get('panel/sms-template/{id}/edit', [SmsTemplatesController::class, 'edit'])->name('sms-template.edit');
    Route::put('panel/sms-template/{id}/edit', [SmsTemplatesController::class, 'update'])->name('sms-template.edit');
    Route::get('panel/sms-template/{id}', [SmsTemplatesController::class, 'view'])->name('sms-template.view');
    Route::delete('panel/sms-templates/delete', [SmsTemplatesController::class, 'delete'])->name('sms-templates.delete');

    // WhatsApp Template Management Module
    Route::get('panel/whatsapp-templates', [WhatsappTemplatesController::class, 'index'])->name('whatsapp-templates');
    Route::post('panel/whatsapp-templates', [WhatsappTemplatesController::class, 'records'])->name('whatsapp-templates.records');
    Route::get('panel/whatsapp-template/add', [WhatsappTemplatesController::class, 'add'])->name('whatsapp-template.add');
    Route::post('panel/whatsapp-template/add', [WhatsappTemplatesController::class, 'store'])->name('whatsapp-template.add');
    Route::get('panel/whatsapp-template/{id}/edit', [WhatsappTemplatesController::class, 'edit'])->name('whatsapp-template.edit');
    Route::put('panel/whatsapp-template/{id}/edit', [WhatsappTemplatesController::class, 'update'])->name('whatsapp-template.edit');
    Route::get('panel/whatsapp-template/{id}', [WhatsappTemplatesController::class, 'view'])->name('whatsapp-template.view');
    Route::delete('panel/whatsapp-templates/delete', [WhatsappTemplatesController::class, 'delete'])->name('whatsapp-templates.delete');

    // Email Template Management Module
    Route::get('panel/email-templates', [EmailTemplatesController::class, 'index'])->name('email-templates');
    Route::post('panel/email-templates', [EmailTemplatesController::class, 'records'])->name('email-templates.records');
    Route::get('panel/email-template/add', [EmailTemplatesController::class, 'add'])->name('email-template.add');
    Route::post('panel/email-template/add', [EmailTemplatesController::class, 'store'])->name('email-template.add');
    Route::get('panel/email-template/{id}/edit', [EmailTemplatesController::class, 'edit'])->name('email-template.edit');
    Route::put('panel/email-template/{id}/edit', [EmailTemplatesController::class, 'update'])->name('email-template.edit');
    Route::get('panel/email-template/{id}', [EmailTemplatesController::class, 'view'])->name('email-template.view');
    Route::delete('panel/email-templates/delete', [EmailTemplatesController::class, 'delete'])->name('email-templates.delete');

    // Settings
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings');
});

