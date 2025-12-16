<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
    // return true;
});

// Defining Channel for each branch
Broadcast::channel('notify.{branchId}', function ($user, $branchId) {
    return (int) $user->branch_id === (int) $branchId;
});

Broadcast::channel('new-order.{branchId}', function ($user, $branchId) {
    return (int) $user->branch_id === (int) $branchId && ($user->isReception() || $user->isManagement());
});

// Order Communication Log Channel - Allow authenticated users to listen to order-specific chat
Broadcast::channel('order-chat.{orderId}', function ($user, $orderId) {
    // Any authenticated user can listen to order chat
    // You can add more specific authorization logic here if needed
    // You can add more specific authorization logic here if needed
    return Auth::check();
});

// Order Status Updates Channel - Allow authenticated users to listen to order updates
Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    return Auth::check();
});

// Task Claims Channel - Allow users to listen to task claims for their role and branch
// Also allow admins to listen to any task claim channel
Broadcast::channel('task-claims.{roleId}.{branchId}', function ($user, $roleId, $branchId) {
    // Allow if user's role and branch match
    $rolesMatch = (int) $user->role_id === (int) $roleId;
    $branchesMatch = (int) $user->branch_id === (int) $branchId;
    
    // Or if user is an admin (admins can monitor any task claims)
    $isAdmin = $user->isAdmin();
    
    return ($rolesMatch && $branchesMatch) || $isAdmin;
});


// Broadcast::channel('App.Models.User.{id}', function($user, $id){
//     Log::info('User ID: %s, Pair User Id: %s', $user->id, $id);
//     return (int) $user->id === (int) $id;
// });


Broadcast::channel('invoice.{id}', function ($user, $id) {
    // Only allow users related to the invoice or admins to listen
    // For now, we'll allow any authenticated user to keep it simple, similar to order-chat
    // You can refine this to check if $user->id == invoice->user_id or if user is admin
    return Auth::check();
});
