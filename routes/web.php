<?php

use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Blog\GuidesController;
use App\Http\Controllers\Blog\KnowledgeBaseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Relocation\ChecklistController;
use App\Http\Controllers\Relocation\RelocationController;
use App\Http\Controllers\Services\BoardingController;
use App\Http\Controllers\Services\GroomingController;
use App\Http\Controllers\Services\PricingController;
use App\Http\Controllers\Services\ServicesController;
use App\Http\Controllers\Services\TrainingController;
use App\Http\Controllers\Services\TransportController;
use App\Http\Controllers\Services\VetCareController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// ---------------------------------------------------------------------------
// Services
// ---------------------------------------------------------------------------
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServicesController::class, 'index'])->name('index');

    Route::prefix('boarding')->name('boarding.')->group(function () {
        Route::get('/', [BoardingController::class, 'index'])->name('index');
        Route::get('/dogs', [BoardingController::class, 'dogs'])->name('dogs');
        Route::get('/cats', [BoardingController::class, 'cats'])->name('cats');
        Route::get('/exotic', [BoardingController::class, 'exotic'])->name('exotic');
    });

    Route::get('/grooming', [GroomingController::class, 'index'])->name('grooming');
    Route::get('/vet-care', [VetCareController::class, 'index'])->name('vet-care');
    Route::get('/training', [TrainingController::class, 'index'])->name('training');
    Route::get('/transport', [TransportController::class, 'index'])->name('transport');
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
});

// Loyalty
Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('loyalty');

// ---------------------------------------------------------------------------
// Relocation
// ---------------------------------------------------------------------------
Route::prefix('relocation')->name('relocation.')->group(function () {
    Route::get('/', [RelocationController::class, 'index'])->name('index');
    Route::get('/import', [RelocationController::class, 'import'])->name('import');
    Route::get('/export', [RelocationController::class, 'export'])->name('export');
    Route::get('/checklist', [ChecklistController::class, 'index'])->name('checklist');
});

// ---------------------------------------------------------------------------
// About
// ---------------------------------------------------------------------------
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/', [AboutController::class, 'index'])->name('index');
    Route::get('/testimonials', [AboutController::class, 'testimonials'])->name('testimonials');
    Route::get('/gallery', [AboutController::class, 'gallery'])->name('gallery');
    Route::get('/careers', [AboutController::class, 'careers'])->name('careers');
    Route::get('/partnerships', [AboutController::class, 'partnerships'])->name('partnerships');
});

// ---------------------------------------------------------------------------
// Blog
// ---------------------------------------------------------------------------
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// ---------------------------------------------------------------------------
// Guides
// ---------------------------------------------------------------------------
Route::prefix('guides')->name('guides.')->group(function () {
    Route::get('/', [GuidesController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [GuidesController::class, 'category'])->name('category');
    Route::get('/{slug}', [GuidesController::class, 'show'])->name('show');
});

// ---------------------------------------------------------------------------
// Knowledge Base
// ---------------------------------------------------------------------------
Route::prefix('knowledge-base')->name('kb.')->group(function () {
    Route::get('/', [KnowledgeBaseController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [KnowledgeBaseController::class, 'category'])->name('category');
    Route::get('/{slug}', [KnowledgeBaseController::class, 'show'])->name('show');
});

// ---------------------------------------------------------------------------
// FAQ
// ---------------------------------------------------------------------------
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// ---------------------------------------------------------------------------
// Contact
// ---------------------------------------------------------------------------
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

// ---------------------------------------------------------------------------
// Newsletter
// ---------------------------------------------------------------------------
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])
    ->middleware('throttle:newsletter')
    ->name('newsletter.subscribe');

// ---------------------------------------------------------------------------
// Shop (placeholder — page not yet built)
// ---------------------------------------------------------------------------
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');

// ---------------------------------------------------------------------------
// Legal
// ---------------------------------------------------------------------------
Route::view('/privacy-policy', 'pages.privacy')->name('privacy');
Route::view('/terms-of-service', 'pages.terms')->name('terms');
Route::view('/cookies-policy', 'pages.cookies')->name('cookies');
