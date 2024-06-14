<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;


Route::resource('posts', PostController::class);

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('Home');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/articles', [PostController::class, 'index'])->name('post.list');
    Route::get('/creation-article', [PostController::class, 'create'])->name('post.create');
    Route::post('/creation', [PostController::class, 'store'])->name('post.store');
    Route::get('/edition-article/{post}', [PostController::class, 'edit'])->name('post.edit');
    Route::post('/update/{post}', [PostController::class, 'update'])->name('post.update');
    Route::get('/detail/{post}', [PostController::class, 'show'])->name('post.show');
    Route::get('/mes-articles', [PostController::class, 'postsUser'])->name('post.user');
    Route::post('/suppression/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::put('/comment/{post}', [CommentController::class, 'store'])->name('comment.store');

    // Route::get('/mes-articles', [PostController::class, 'index'])->name('post.list');
    // Route::get('/mes-avis', [PostController::class, 'index'])->name('post.list');
    // Route::get('/recherche', [PostController::class, 'index'])->name('post.list');
    // Route::get('/messagerie', [PostController::class, 'index'])->name('post.list');
});

require __DIR__.'/auth.php';
