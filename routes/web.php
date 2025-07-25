<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\FrontendController;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about-us');

Route::get('/services', [FrontendController::class, 'services'])->name('services.index');
Route::get('/services/{slug}', [FrontendController::class, 'showService'])->name('services.show');
Route::get('/load-more-services', [FrontendController::class, 'loadMoreService'])->name('services.loadMore');

Route::get('/news/', [FrontendController::class, 'blogs'])->name('blog');
Route::get('/load-more-blogs', [FrontendController::class, 'loadMoreBlogs'])->name('blog.loadMore');
Route::get('/news/{slug}', [FrontendController::class, 'showBlog'])->name('blog.details');

Route::get('/generate-slug', [CategoryController::class, 'generateSlug'])->name('generate-slug');

Route::get('/tutorials', [FrontendController::class, 'tutorials'])->name('tutorial.index');
Route::get('/load-more-tutorials', [FrontendController::class, 'loadMoreTutorials'])->name('tutorial.loadMore');
Route::get('/tutorial/{slug}', [FrontendController::class, 'showTutorial'])->name('tutorial.details');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact-submit', [FrontendController::class, 'submitContactForm'])->name('contact.submit');

Route::post('/subscribe', [FrontendController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/terms-conditions', [FrontendController::class, 'terms'])->name('terms-conditions');
Route::get('/privacy-policy', [FrontendController::class, 'privacy'])->name('privacy-policy');
Route::get('/return-policy', [FrontendController::class, 'returnPolicy'])->name('return-policy');

Route::get('/faq', [FrontendController::class, 'faq'])->name('faq');
