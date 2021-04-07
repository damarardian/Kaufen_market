<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
$url = "App\Http\Controllers";

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'App\Http\Controllers\UserController@login')->middleware('cekverified')->name('login');


Route::post('/email/resend',$url . '\VerificationApiController@resend')->name('verification.resend');   
Route::get('/email/verify/{id}', $url .'\VerificationApiController@verify')->name('verification.verify');
Route::post('password/email', 'App\Http\Controllers\ResetPasswordController@forgot');
Route::post('password/reset', 'App\Http\Controllers\ResetPasswordController@reset');


// All Users
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/logout', 'App\Http\Controllers\UserController@logout');
    Route::get('user/detail', 'App\Http\Controllers\UserController@details');                       // Detail Current User
    // Route::get('user/{id}', 'App\Http\Controllers\UserController@getUserById');                  // Detail Current User
    
    // History
    Route::get('/barang-koperasi/history', 'App\Http\Controllers\KoperasiController@history');      // History Barang Koperasi
    Route::get('/barang-penjual/history', 'App\Http\Controllers\BarangController@history');         // History Barang Koperasi
    Route::get('/pinjam/history', 'App\Http\Controllers\LoansController@history');                  // History Loan
    Route::get('/nabung/history', 'App\Http\Controllers\DepositController@history');                // Deposit Loan

    /// Transaksi history
        Route::post('/transaksi/dec-stock', 'App\Http\Controllers\DecstockController@store');        // Barang Transaksi
        Route::get('/transaksi/history', 'App\Http\Controllers\DecstockController@history');         // History Barang Koperasi
        Route::get('/bayar-pinjam/history', 'App\Http\Controllers\LoansController@payHistory');      // History Bayar Pinjaman
        Route::get('/bayar-pinjam/accepted/history', 'App\Http\Controllers\LoansController@payAcceptedHistory');      // History Bayar Pinjaman


    // Update User
        Route::put('/user/update-profile', 'App\Http\Controllers\UserController@updateProf');       // Update Profile      
        Route::get('change-password', 'App\Http\Controllers\ChangePasswordController@index');
        Route::post('change-password', 'App\Http\Controllers\ChangePasswordController@store')->name('change.password'); // Change Password

    // Delete User
        Route::delete('/user/delete', 'App\Http\Controllers\UserController@delete');                // self deleting

    // Data Diri
        Route::post('/data/data-diri', 'App\Http\Controllers\DataController@store');                 // Add data diri    
        //Route::get('/data/show', 'App\Http\Controllers\DataController@showSelf');                  // show data diri diambil dari user/detail
        Route::put('/data/edit', 'App\Http\Controllers\DataController@updateSelf'); 
        Route::post('/user/update-image', 'App\Http\Controllers\UserController@updateImage'); 
        Route::resource('/data', 'App\Http\Controllers\DataController');                             // Full Controll data diri all members
    
    // Pay & Request

        // Loans
        Route::post('pinjam-pay', 'App\Http\Controllers\LoansController@requestPay');                // Pay Req
        Route::post('pinjam-req', 'App\Http\Controllers\LoansController@store');                // Loan Req
        Route::post('pinjam-del/{id}', 'App\Http\Controllers\LoansController@destroy');                // Loan Req
        Route::put('pinjam-edit/{id}', 'App\Http\Controllers\LoansController@update');                // Loan Req

        // Deposit
        Route::post('/nabung-req', 'App\Http\Controllers\DepositController@store');    
        Route::put('/nabung-edit/{id}', 'App\Http\Controllers\DepositController@update');    
        Route::get('/nabung-req/list', 'App\Http\Controllers\DepositController@index');    



// REKAPAN
    Route::get('/barang-koperasi/show', 'App\Http\Controllers\KoperasiController@index');           // Koperasi
    Route::get('/barang-penjual/show', 'App\Http\Controllers\BarangController@index');              // Barang Titipan
    Route::get('/pinjam/show', 'App\Http\Controllers\LoansController@index');                       // Peminjaman
    Route::get('/nabung/show', 'App\Http\Controllers\DepositController@index');                     // Tabungan    
});

// Access Verified Email
// Route::group(['middleware' => 'auth:api', 'verified'], function() {

// }); 

// Admin Role
Route::group(['middleware' => ['auth:api', 'role:admin']], function() {        
    Route::get('/user/{role}', 'App\Http\Controllers\UserController@getUsersByRole');               // Get All User By Role   
    Route::get('/users/{id}', 'App\Http\Controllers\UserController@getUserByID');                    // Get User By ID
    Route::get('/users', 'App\Http\Controllers\UserController@allDetails');                         // Get All User With Data
    Route::post('/register', 'App\Http\Controllers\UserController@register');

    Route::resource('/pinjam', 'App\Http\Controllers\LoansController');
    Route::resource('/nabung', 'App\Http\Controllers\DepositController');    

    Route::resource('/barang-koperasi', 'App\Http\Controllers\KoperasiController'); 
    Route::post('/barang-koperasi/update-image/{id}', 'App\Http\Controllers\KoperasiController@updateImage');

    Route::resource('/barang-penjual', 'App\Http\Controllers\BarangController');
    Route::post('/barang-penjual/update-image/{id}', 'App\Http\Controllers\BarangController@updateImage');

    Route::delete('/user/delete/{id}', 'App\Http\Controllers\UserController@deleteMember');         // Delete Member

    Route::get('/pay-req/list', 'App\Http\Controllers\LoansController@payRequestList');             // Show Pay Request
    Route::post('/pay-accept/{id}', 'App\Http\Controllers\LoansController@payAccepting');           // Accepting Pay request

    Route::get('/pinjam-req/list', 'App\Http\Controllers\LoansController@loanRequestList');            // Show Loan Request
    Route::post('/pinjam-accept/{id}', 'App\Http\Controllers\LoansController@loanAccepting');         // Accepting Pay Request

});


// Anggota Role
 Route::group(['middleware' => ['auth:api', 'role:anggota']], function() {
    
});


// penjual Role
Route::group(['middleware' => ['auth:api', 'role:penjual']], function() {    
    Route::put('barang-koperasi-transaksi/update/{id}', 'App\Http\Controllers\KoperasiController@update'); 
    Route::put('/barang-penjual-transaksi/update/{id}', 'App\Http\Controllers\BarangController@update'); 
    Route::put('/barang-koperasi/stock/{id}', 'App\Http\Controllers\KoperasiController@transaksi');   // Transaksi Barang koperasi
    Route::put('/barang-penjual/stock/{id}', 'App\Http\Controllers\BarangController@transaksi');   // Transaksi Barang Titipan
    
});

