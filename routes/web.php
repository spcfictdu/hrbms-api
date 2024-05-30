<?php

use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('https://hotel.osafphmabalacatcity.com/');
});

Route::get('/email', function () {
    $transaction = Transaction::first();
    return view("emails/reserve_transaction", compact('transaction'));
});
Route::get('/vanilla', function () {
    $transaction = Transaction::first();
    return view("emails/reserve_confirmation", compact('transaction'));
});
