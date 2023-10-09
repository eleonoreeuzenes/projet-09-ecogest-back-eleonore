<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserPointCategoryController;
use App\Http\Controllers\Api\UserPostParticipationController;
use App\Http\Controllers\Api\UserTrophyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/test', fn() => json_encode(["test", "test"]));

// Authentication
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
    // User 
    Route::get('/me', [UserController::class, 'getUserData']);
    Route::patch('/me', [UserController::class, 'update']);
    Route::delete('/me', [UserController::class, 'destroy']);

    // User post participation
    Route::get('posts/{postId}/participants', [UserPostParticipationController::class, 'getParticipantsByPostId']);
    Route::post('posts/{postId}/participants', [UserPostParticipationController::class, 'store']);
    Route::patch('posts/{postId}/participants', [UserPostParticipationController::class, 'update']);
    Route::delete('posts/{postId}/participants/{userId}', [UserPostParticipationController::class, 'destroy']);
    // end a challenge 
    Route::patch('posts/{postId}/participants/completed', [UserPostParticipationController::class, 'endChallenge']);

    Route::post('/posts/{postId}/likes', [LikeController::class, 'likePost']);
    Route::delete('/posts/{postId}/likes', [LikeController::class, 'unlikePost']);

    
    Route::get('users/{userId}/posts/completed', [UserPostParticipationController::class, 'getPostsByUserCompleted']);
    Route::get('users/{userId}/posts/in-progress', [UserPostParticipationController::class, 'getPostsByUserInProgress']);
    Route::get('users/{userId}/posts/abandoned', [UserPostParticipationController::class, 'getPostsByUserAbandoned']);
    Route::get('users/{userId}/posts', [UserPostParticipationController::class, 'getPostsByUser']);

    // API business routes
    Route::apiResources([
        'posts'       => PostController::class, // posts?page=1 => 30 firsts posts; posts?page=2 => 30 next posts
        'categories'  => CategoryController::class, 
        'users/{userId}/categories-points'  => UserPointCategoryController::class, // user points in categories
        'users/{userId}/trophies'  => UserTrophyController::class, // user trophies
    ]);
});
