<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use App\Models\Guide;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class DevelopmentSeederTest extends TestCase
{
    public function test_development_dataset_is_repeatable_and_stays_on_the_configured_test_database(): void
    {
        $connection = config('database.default');

        $this->assertSame(
            config("database.connections.{$connection}.database"),
            DB::connection()->getDatabaseName(),
        );
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        foreach ([
            'users' => 1,
            'business_profiles' => 1,
            'guides' => 4,
            'knowledge_articles' => 10,
            'faqs' => 52,
            'gallery_items' => 23,
            'testimonials' => 12,
            'products' => 10,
            'job_openings' => 2,
            'booking_requests' => 0,
            'contact_enquiries' => 0,
            'newsletter_subscriptions' => 0,
            'media' => 37,
        ] as $table => $count) {
            $this->assertDatabaseCount($table, $count);
        }

        $this->assertSame(7, DB::table('business_hours')->where('kind', 'weekly')->count());
        $this->assertSame(14, DB::table('media')->where('collection_name', 'cover')->count());
        $this->assertSame(23, DB::table('media')->where('collection_name', 'image')->count());
    }

    public function test_reseeding_preserves_editorial_changes_and_existing_media(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);

        $guide = Guide::query()->firstOrFail();
        $mediaId = $guide->getFirstMedia('cover')?->getKey();
        DB::table('guides')->where('id', $guide->getKey())->update([
            'title' => 'Editorially revised guide',
        ]);

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('guides', [
            'id' => $guide->getKey(),
            'title' => 'Editorially revised guide',
        ]);
        $this->assertDatabaseHas('media', [
            'id' => $mediaId,
            'model_type' => Guide::class,
            'model_id' => $guide->getKey(),
            'collection_name' => 'cover',
        ]);
        $this->assertSame(23, GalleryItem::query()->count());
    }
}
