<?php

use Illuminate\Support\Facades\Route;

Route::get('/received', function () {
    return view('received');
});
Route::get('/checkin', function () {
    return view('checkin');
});


