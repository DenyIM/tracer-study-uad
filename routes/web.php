<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.main-kuesioner');
});

Route::get('/nav-kuesioner', function () {
    return view('pages.main-kuesioner');
});

Route::get('/nav-leaderboard', function () {
    return view('pages.leaderboard');
});

Route::get('/nav-forum', function () {
    return view('pages.forum');
});

Route::get('/nav-mentor', function () {
    return view('pages.mentor');
});

Route::get('/nav-lowongan', function () {
    return view('pages.list-lowongan');
});

Route::get('/nav-profile', function () {
    return view('pages.profile');
});

Route::get('/nav-bookmark', function () {
    return view('pages.bookmark');
});

Route::get('/logout', function () {
    return view('pages.homepage');
});

Route::get('/detlow', function () {
    return view('pages.lowongan');
});
