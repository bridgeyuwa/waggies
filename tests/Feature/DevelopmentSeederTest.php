<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class DevelopmentSeederTest extends TestCase
{
    public function test_development_dataset_is_repeatable_and_stays_on_the_test_database(): void
    {
        $this->assertSame('waggies_test', DB::connection()->getDatabaseName());

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
            'clinical_contents' => 0,
            'clinical_sources' => 0,
            'media' => 0,
        ] as $table => $count) {
            $this->assertDatabaseCount($table, $count);
        }

        $this->assertSame(7, DB::table('business_hours')->where('kind', 'weekly')->count());
    }
}
