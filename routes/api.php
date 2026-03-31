<?php
use App\Http\Controllers\PaymentCallbackController;

Route::post('/payment/callback', [PaymentCallbackController::class, 'callback']);