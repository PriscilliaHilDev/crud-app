<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;


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
    Route::patch('/edition-commentaire/{comment}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/suppression-commentaire/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
    Route::get('/recherche', [SearchController::class, 'index'])->name('search');
    Route::get('/recherche/articles', [SearchController::class, 'getPosts'])->name('search.posts');
    Route::get('/recherche/resultats', [SearchController::class, 'getPostsByPage'])->name('search.posts.page');
    Route::get('/test', [SearchController::class, 'testCode'])->name('test.index');
    Route::get('/data-test', [SearchController::class, 'testData'])->name('test.data');
});



require __DIR__.'/auth.php';
