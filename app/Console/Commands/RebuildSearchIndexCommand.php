<?php

namespace App\Console\Commands;

use App\Support\SearchDocumentSynchronizer;
use Illuminate\Console\Command;

final class RebuildSearchIndexCommand extends Command
{
    protected $signature = 'waggies:search-rebuild';

    protected $description = 'Rebuild the public Waggies Scout search projection';

    public function handle(SearchDocumentSynchronizer $synchronizer): int
    {
        $this->info('Rebuilding the public search projection...');
        $count = $synchronizer->rebuild();
        $this->comment("Synchronized {$count} public search documents.");

        return self::SUCCESS;
    }
}
