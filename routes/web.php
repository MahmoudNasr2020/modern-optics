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

// دليل المستخدم — صفحة مستقلة بدون أي ربط بالداشبورد
Route::get('/manual', function () {
    return view('manual');
})->name('manual');

// Avatar generator — يولّد صورة SVG بالأحرف الأولى للاسم
Route::get('/avatar/{initials}', function ($initials) {
    $initials = strtoupper(substr(urldecode($initials), 0, 2));

    $colors = [
        '#6366f1', '#8b5cf6', '#ec4899', '#f59e0b',
        '#10b981', '#3b82f6', '#ef4444', '#14b8a6',
    ];
    // Pick a consistent color based on initials
    $color = $colors[abs(crc32($initials)) % count($colors)];

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128">
  <rect width="128" height="128" rx="64" fill="{$color}"/>
  <text x="64" y="64" dominant-baseline="central" text-anchor="middle"
        font-family="Arial, sans-serif" font-size="52" font-weight="700" fill="#ffffff">
    {$initials}
  </text>
</svg>
SVG;

    return response($svg, 200, [
        'Content-Type'  => 'image/svg+xml',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('avatar');
