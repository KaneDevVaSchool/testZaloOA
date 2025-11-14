<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GuestController;

Route::prefix('guests')->group(function () {
    // Admin CRUD
    Route::get('/', [GuestController::class, 'index'])->name('guests.index');
    Route::get('/create', [GuestController::class, 'create'])->name('guests.create');
    Route::post('/', [GuestController::class, 'store'])->name('guests.store');
    Route::get('/{guest}/edit', [GuestController::class, 'edit'])->name('guests.edit');
    Route::put('/{guest}', [GuestController::class, 'update'])->name('guests.update');
    Route::delete('/{guest}', [GuestController::class, 'destroy'])->name('guests.destroy');

    // QR routes
    Route::get('/qr/{qr_token}', [GuestController::class, 'showByQr'])->name('guests.qr.show'); // xem info khi quét QR
    Route::get('/qr-image/{qr_token}', [GuestController::class, 'qrImage'])->name('guests.qr.image'); // sinh QR image

    // Scan QR (POST)
    Route::post('/scan', [GuestController::class, 'scanQr'])->name('guests.qr.scan');
    Route::get('/status/{qr_token}', function ($qr_token) {
        $guest = \App\Models\Guest::where('qr_token', $qr_token)->firstOrFail();
        return response()->json([
            'status' => $guest->status,
            'number' => $guest->number,
        ]);
    })->name('guests.status');

});