<?php

namespace App\Observers;

use App\Support\SearchDocumentSynchronizer;
use Illuminate\Database\Eloquent\Model;

final class SearchContentObserver
{
    public function __construct(private readonly SearchDocumentSynchronizer $synchronizer) {}

    public function saved(Model $model): void
    {
        $this->synchronizer->syncModel($model);
    }

    public function deleted(Model $model): void
    {
        $this->synchronizer->removeModel($model);
    }
}
