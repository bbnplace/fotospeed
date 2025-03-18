<?php

use App\Http\Controllers\CustomerInvoicesController;
use App\Http\Controllers\Messaging\WhatsappController;
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
    return $request->user();
});

Route::match(['get', 'post'], '/whatsapp/inbound', [WhatsappController::class, 'inbound']);

Route::post('/payment/done', [CustomerInvoicesController::class, 'paymentCompleted'])->name('paystack.complete');