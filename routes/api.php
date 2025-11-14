<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GuestController;

Route::prefix('api/guests')->group(function () {
    Route::get('/{qr_token}', [GuestController::class, 'showByQr'])->name('api.guests.show');
    Route::get('/checkin/{qr_token}', [GuestController::class, 'checkInQRCode'])->name('api.guests.checkin');
    Route::post('/scan', [GuestController::class, 'scanQr'])->name('api.guests.scan');
    Route::get('/qr-image/{qr_token}', [GuestController::class, 'qrImage'])->name('api.guests.qr_image');
    Route::get('/status/{qr_token}', [GuestController::class, 'status'])->name('api.guests.status');
});