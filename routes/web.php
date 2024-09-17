<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::resource('posts', PostController::class);

Route::get('/', [AuthenticatedSessionController::class, 'HomeNotLogged']);

// Middleware pour les utilisateurs authentifiés (sans vérification d'email)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes liées aux articles (posts)
    Route::get('/articles', [PostController::class, 'index'])->name('post.list');
    Route::get('/creation-article', [PostController::class, 'create'])->name('post.create');
    Route::post('/creation', [PostController::class, 'store'])->name('post.store');
    Route::get('/edition-article/{post}', [PostController::class, 'edit'])->name('post.edit');
    Route::post('/update/{post}', [PostController::class, 'update'])->name('post.update');
    Route::get('/detail/{post}', [PostController::class, 'show'])->name('post.show');
    Route::get('/mes-articles', [PostController::class, 'postsUser'])->name('post.user');
    Route::post('/suppression/{post}', [PostController::class, 'destroy'])->name('post.destroy');

    // Routes pour les commentaires
    Route::put('/comment/{post}', [CommentController::class, 'store'])->name('comment.store');
    Route::patch('/edition-commentaire/{comment}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/suppression-commentaire/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');

    // Routes pour la recherche
    Route::get('/recherche', [SearchController::class, 'index'])->name('search');
    Route::get('/recherche/articles', [SearchController::class, 'getPosts'])->name('search.posts');
    Route::get('/recherche/resultats', [SearchController::class, 'getPostsByPage'])->name('search.posts.page');
    // Route::get('/test', [SearchController::class, 'testCode'])->name('test.index');

    // Route pour la gestion de la lecture des notifications
    Route::get('/notifications', [NotificationController::class, 'getRecentNotifications']);
    // Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
});



require __DIR__.'/auth.php';
