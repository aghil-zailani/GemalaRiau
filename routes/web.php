<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE OTENTIKASI MANUAL ---
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// --- RUTE HALAMAN PUBLIK ---
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/search', [PageController::class, 'search'])->name('search');
Route::post('/newsletter/subscribe', [PageController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/news/{slug}', [PageController::class, 'show'])->name('news.show');
Route::get('/categories', [PageController::class, 'categoriesIndex'])->name('categories.index');
Route::get('/search', [PageController::class, 'search'])->name('search');

// --- RUTE PANEL ADMIN ---
Route::prefix('admin')
    ->middleware(['auth'])
    ->name('admin.')
    ->group(function () {
        
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('articles', ArticleController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::patch('articles/{article}/toggle-status', [ArticleController::class, 'toggleStatus'])->name('articles.toggleStatus');
    Route::post('ckeditor/upload', [ArticleController::class, 'upload'])->name('ckeditor.upload');
});

// --- RUTE HALAMAN PUBLIK (LANJUTAN) ---
Route::get('/{slug}', [PageController::class, 'category'])
    ->where('slug', '[a-zA-Z0-9-]+')
    ->name('category.show');
