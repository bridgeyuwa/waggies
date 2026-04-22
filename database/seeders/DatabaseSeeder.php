<?php

namespace Database\Seeders;

use App\Models\Enquiry;
use App\Models\KbArticle;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Blog posts — 24 published, 3 drafts
        Post::factory(24)->blog()->create();
        Post::factory(3)->blog()->draft()->create();

        // Guides — 12 published, 2 drafts
        Post::factory(12)->guide()->create();
        Post::factory(2)->guide()->draft()->create();

        // KB articles — 30 published, 6 featured
        KbArticle::factory(24)->create();
        KbArticle::factory(6)->featured()->create();

        // Sample enquiries
        Enquiry::factory(15)->create();

        // Newsletter subscribers
        Subscriber::factory(40)->create();

        // FAQs
        $this->call(FaqSeeder::class);
    }
}
