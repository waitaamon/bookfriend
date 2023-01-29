<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\HomeController::class);

Route::get('auth/register', \App\Http\Controllers\RegisterIndexController::class);
Route::get('auth/login', \App\Http\Controllers\LoginController::class);

Route::post('books', \App\Http\Controllers\BookStoreController::class);
Route::get('books/create', \App\Http\Controllers\BookCreateController::class);
Route::get('books/{book}/edit', \App\Http\Controllers\BookEditController::class);
Route::put('books/{book}', \App\Http\Controllers\BookPutController::class);

Route::get('friends', \App\Http\Controllers\FriendIndexController::class);
Route::post('friends', \App\Http\Controllers\FriendStoreController::class);
Route::patch('friends/{friend}', \App\Http\Controllers\FriendPatchController::class);
Route::delete('friends/{friend}', \App\Http\Controllers\FriendDestroyController::class);


Route::get('feed', \App\Http\Controllers\FeedIndexController::class);
