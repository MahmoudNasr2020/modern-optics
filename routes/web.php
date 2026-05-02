<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// الصفحة الرئيسية → صفحة الهبوط
Route::get('/', function () {
    return view('welcome');
})->name('home.landing');

// /dashboard/login → لو مسجل: داشبورد | لو لأ: صفحة اللوجن
Route::get('/dashboard/login', function () {
    if (auth()->check()) {
        return redirect('/dashboard/home');
    }
    return redirect('/login');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
