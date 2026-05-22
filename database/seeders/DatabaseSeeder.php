<?php

namespace Database\Seeders;

use App\Models\Enquiry;
use App\Models\KbArticle;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local') || env('ADMIN_EMAIL')) {
            User::firstOrCreate(
                ['email' => env('ADMIN_EMAIL', 'admin@waggies.test')],
                [
                    'name' => env('ADMIN_NAME', 'Admin'),
                    'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                ],
            );
        }

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
