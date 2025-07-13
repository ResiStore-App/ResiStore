<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrukController;

Route::get('/', function () {
    return redirect('admin/login');
});

Route::get('/print-struk/{id}', [StrukController::class, 'print'])->name('struk.print');
