<?php

use App\Http\Controllers\LandingPage\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index']);
