<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class KnowledgeSource extends Model
{
    protected $fillable = [
        'source_key',
        'title',
        'source_type',
        'source_reference',
        'public_url',
        'content_hash',
        'status',
        'synced_at',
        'provider_file_id',
        'vector_store_id',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'synced_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
