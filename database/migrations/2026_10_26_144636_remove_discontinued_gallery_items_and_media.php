<?php

use App\Models\GalleryItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        GalleryItem::query()
            ->whereIn('category', ['Grooming', 'Training'])
            ->get()
            ->each(static function (GalleryItem $galleryItem): void {
                $galleryItem->delete();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        throw new RuntimeException('This migration deletes discontinued gallery records and their media files, so it cannot be rolled back safely.');
    }
};
