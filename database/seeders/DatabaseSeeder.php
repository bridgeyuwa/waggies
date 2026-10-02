<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! in_array(config('app.env'), ['local', 'testing'], true)) {
            return;
        }

        $admin = User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            User::factory()->make([
                'name' => 'Test Admin',
                'email' => 'test@example.com',
                'is_admin' => true,
            ])->getAttributes(),
        );

        if (! $admin->is_admin) {
            $admin->forceFill(['is_admin' => true])->save();
        }

        $this->call([
            WaggiesContentSeeder::class,
            DevelopmentDatasetSeeder::class,
        ]);
    }
}
