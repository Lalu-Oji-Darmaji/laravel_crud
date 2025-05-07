<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;

Route::resource('/list', PostController::class);

Route::get('/', function () {
    return redirect('/list');
});
