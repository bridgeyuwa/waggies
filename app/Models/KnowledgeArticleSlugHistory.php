<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class KnowledgeArticleSlugHistory extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'knowledge_article_id',
        'slug',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function knowledgeArticle(): BelongsTo
    {
        return $this->belongsTo(KnowledgeArticle::class);
    }
}
