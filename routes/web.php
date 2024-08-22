<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

/* exams function*/

Route::middleware(['CheckUserRole'])->group(function () {
    //admin
    Route::get('/admin',[AdminController::class,'index'])->name('home');
    //Exam
    Route::resource('exams',ExamController::class)->only([
        'index', 'create', 'store', 'show','edit', 'update', 'destroy'
    ]);
    //Topic
    Route::resource('topic',PostController::class)->only([
        'index', 'create', 'store', 'show','edit', 'update', 'destroy'
    ]);

    Route::resource('users',UserController::class)->only([
        'index', 'create', 'store', 'show','edit', 'update', 'destroy'
    ]);

    Route::resource('comment',CommentController::class)->only([
        'index', 'create', 'store', 'show','edit', 'update', 'destroy'
    ]);

    Route::post('/create',[ExamController::class,'handleCreateForm'])->name('create_exams');

    Route::post('/create_post', [PostController::class, 'handleCreatePostForm'])->name('post_create');

    Route::post('/translation/store', [TranslationController::class, 'storeTranslate'])->name('translation.store');

});





/*-------------------------------------------------------*/

Route::get('/',[MainPageController::class,'index'])->name('index');
Route::get('/exam/{id}',[MainPageController::class,'Exam'])->name('exam');
Route::get('/question/{exam}',[MainPageController::class,'Question'])->name('question');
Route::post('/question/submit{id}', [MainPageController::class, 'submitAnswers'])->name('question.submit');
Route::get('/user-progress', [MainPageController::class, 'getUserProgress'])->name('user.progress');
Route::get('/about-us', [MainPageController::class, 'about_us'])->name('user.about_us');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login_user');
Route::get('/register',[AuthController::class,'showRegistrationForm'])->name('register');
Route::post('/register',[AuthController::class,'register'])->name('regiter_user');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes for password reset
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'checkEmail'])->name('password.email');
Route::match(['get', 'post'], '/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/translate',[TranslationController::class,'index'])->name('index_translate');
Route::post('/translate',[TranslationController::class,'translate'])->name('translate');
Route::get('/translations/{cacheKey}', [TranslationController::class, 'show'])->name('translation.show');

Route::get('/posts', [StudyController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [StudyController::class, 'show'])->name('posts.show');
Route::post('/posts/{newsId}/savecomment', [StudyController::class, 'saveComment'])->name('posts.saveComment');


Route::get('/lang/{locale}', [LanguageController::class, 'changeLanguage'])->name('changeLanguage');


Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
Route::post('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::get('/profile/password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.password');
Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.update');



Route::get('/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback']);
