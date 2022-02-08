<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;

Route::get('firebase-phone-authentication', [FirebaseController::class, 'index']);

