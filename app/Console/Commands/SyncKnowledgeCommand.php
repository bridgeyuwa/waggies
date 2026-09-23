<?php

namespace App\Console\Commands;

use App\Models\KnowledgeSource;
use App\Models\SearchDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Files;
use Laravel\Ai\Stores;
use Throwable;

final class SyncKnowledgeCommand extends Command
{
    protected $signature = 'waggies:knowledge-sync';

    protected $description = 'Sync approved public Waggies content to the AI knowledge manifest and optional vector store';

    public function handle(): int
    {
        $provider = (string) (config('services.waggies_ai.sync_provider') ?: config('services.waggies_ai.provider'));
        $storeId = (string) config('services.waggies_ai.vector_store_id', '');
        $providerReady = config('services.waggies_ai.enabled', true)
            && ($provider === 'ollama' || filled(config("ai.providers.{$provider}.key")));

        if ($providerReady && $storeId === '' && config('services.waggies_ai.create_vector_store', false)) {
            try {
                $storeId = Stores::create('Waggies public knowledge', 'Approved public Waggies website content.', provider: $provider)->id;
                $this->info("Created vector store {$storeId}.");
            } catch (Throwable $exception) {
                $this->warn('The provider vector store could not be created; keeping a local manifest only.');
            }
        }

        $documents = SearchDocument::query()->public()->orderBy('id')->get();
        $activeKeys = $documents->map(fn (SearchDocument $document): string => $document->source_type.':'.$document->source_key);
        $synced = 0;
        $uploaded = 0;

        foreach ($documents as $document) {
            $sourceKey = $document->source_type.':'.$document->source_key;
            $path = 'waggies-knowledge/'.preg_replace('/[^a-z0-9_-]+/i', '-', $sourceKey).'.md';
            $content = $this->markdownFor($document);
            $hash = hash('sha256', $content);
            $existing = KnowledgeSource::query()->where('source_key', $sourceKey)->first();
            $providerFileId = $existing === null ? null : $existing->provider_file_id;

            Storage::disk('local')->put($path, $content);

            if ($providerReady && $storeId !== '' && ($existing === null || $existing->content_hash !== $hash || $existing->vector_store_id !== $storeId)) {
                $store = Stores::get($storeId, provider: $provider);

                if ($existing !== null && $existing->provider_file_id && $existing->vector_store_id === $storeId) {
                    $store->remove($existing->provider_file_id, deleteFile: true);
                }

                $file = Files::putFromStorage($path, 'local', basename($path), provider: $provider);
                $providerFileId = $store->add($file, [
                    'status' => 'approved',
                    'source_key' => $sourceKey,
                    'source_type' => $document->source_type,
                ])->fileId() ?: $file->id();
                $uploaded++;
            }

            KnowledgeSource::query()->updateOrCreate(
                ['source_key' => $sourceKey],
                [
                    'title' => $document->title,
                    'source_type' => $document->source_type,
                    'source_reference' => $path,
                    'public_url' => $document->url,
                    'content_hash' => $hash,
                    'status' => 'approved',
                    'synced_at' => now(),
                    'provider_file_id' => $providerFileId,
                    'vector_store_id' => $storeId !== '' ? $storeId : null,
                    'metadata' => ['category' => $document->category, 'section' => $document->section],
                ],
            );
            $synced++;
        }

        $staleSources = KnowledgeSource::query()
            ->where('status', 'approved')
            ->when($activeKeys->isNotEmpty(), fn ($query) => $query->whereNotIn('source_key', $activeKeys->all()))
            ->get();

        foreach ($staleSources as $source) {
            if ($providerReady && $storeId !== '' && $source->provider_file_id && $source->vector_store_id === $storeId) {
                Stores::get($storeId, provider: $provider)->remove($source->provider_file_id, deleteFile: true);
            }

            $source->update([
                'status' => 'stale',
                'provider_file_id' => null,
                'vector_store_id' => null,
                'synced_at' => now(),
            ]);
        }

        $this->info("Synchronized {$synced} approved knowledge sources.");
        $this->comment($uploaded > 0 ? "Uploaded {$uploaded} changed sources to {$provider}." : 'No provider uploads were required.');

        return self::SUCCESS;
    }

    private function markdownFor(SearchDocument $document): string
    {
        return collect([
            '# '.$document->title,
            'Category: '.($document->category ?: $document->section),
            $document->excerpt,
            $document->body,
            'Public page: '.$document->url,
        ])->filter()->implode("\n\n");
    }
}
