<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AboutPagesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GuidesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\RelocationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ToolsController;
use App\Support\PublicUrlCatalog;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::controller(AboutPagesController::class)->group(function (): void {
    Route::get('/about/testimonials', 'testimonials')->name('about.testimonials');
    Route::get('/about/gallery', 'gallery')->name('about.gallery');
    Route::get('/about/careers', 'careers')->name('about.careers');
    Route::get('/about/partnerships', 'partnerships')->name('about.partnerships');
});
Route::controller(ServicesController::class)->group(function (): void {
    Route::get('/services', 'index')->name('services.index');
    Route::get('/services/boarding', 'boarding')->name('services.boarding');
    Route::get('/services/boarding/{species}', 'boardingSpecies')->whereIn('species', ['dogs', 'cats', 'exotic'])->name('services.boarding.species');
    Route::get('/services/grooming', 'grooming')->name('services.grooming');
    Route::get('/services/training', 'training')->name('services.training');
    Route::get('/services/vet-care', 'vetCare')->name('services.vet-care');
});
Route::get('/services/pricing', [PricingController::class, 'index'])->name('services.pricing');
Route::controller(RelocationController::class)->group(function (): void {
    Route::get('/services/relocation', 'index')->name('services.relocation');
    Route::get('/services/relocation/import', 'import')->name('relocation.import');
    Route::get('/services/relocation/export', 'export')->name('relocation.export');
    Route::get('/services/relocation/transport', 'transport')->name('relocation.transport');
    Route::get('/services/relocation/checklist', 'checklist')->name('relocation.checklist');
});
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/privacy-policy', [LegalController::class, 'privacy'])->name('privacy-policy');
Route::get('/terms-of-service', [LegalController::class, 'terms'])->name('terms-of-service');
Route::get('/cookies-policy', [LegalController::class, 'cookies'])->name('cookies-policy');
Route::get('/contact', ContactController::class)->name('contact');
Route::get('/loyalty', LoyaltyController::class)->name('loyalty');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}', [ShopController::class, 'show'])->where('id', '[a-z0-9-]+')->name('shop.show');
Route::get('/guides', [GuidesController::class, 'index'])->name('guides.index');
Route::get('/guides/{slug}', [GuidesController::class, 'show'])->name('guides.show');
Route::get('/knowledge-base', [KnowledgeBaseController::class, 'index'])->name('knowledge-base.index');
Route::get('/knowledge-base/{slug}', [KnowledgeBaseController::class, 'show'])->name('knowledge-base.show');
Route::controller(ToolsController::class)->group(function (): void {
    Route::get('/tools', 'index')->name('tools.index');
    Route::get('/tools/symptom-checker', 'symptomChecker')->name('tools.symptom-checker');
    Route::get('/tools/vaccination-schedule', 'vaccination')->name('tools.vaccination');
    Route::get('/tools/parasite-schedule', 'parasite')->name('tools.parasite');
    Route::get('/tools/emergency-guide', 'emergency')->name('tools.emergency');
    Route::get('/tools/medication-dosage-guide', 'medication')->name('tools.medication');
    Route::get('/tools/pet-age-calculator', 'petAge')->name('tools.pet-age');
    Route::get('/tools/cost-calculator', 'cost')->name('tools.cost');
    Route::get('/tools/nutrition-calculator', 'nutrition')->name('tools.nutrition');
    Route::get('/tools/breed-finder', 'breedFinder')->name('tools.breed-finder');
    Route::get('/tools/behavior-tips', 'behaviorTips')->name('tools.behavior-tips');
    Route::get('/tools/new-pet-checklist', 'newPetChecklist')->name('tools.new-pet-checklist');
});
Route::post('/api/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
Route::get('/api/search', SearchController::class)->name('search');
Route::get('/sitemap.xml', fn (): Sitemap => Sitemap::create()->add((new PublicUrlCatalog)->urls()))->name('sitemap');
Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /api/\nSitemap: ".route('sitemap')."\n", 200, ['Content-Type' => 'text/plain']);
})->name('robots');
