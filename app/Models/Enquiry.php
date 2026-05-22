<?php

namespace App\Models;

use Database\Factories\EnquiryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    /** @use HasFactory<EnquiryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'message',
        'service',
        'variant',
        'tier',
        'intent',
        'estimate_summary',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function markRead(): void
    {
        $this->update(['read_at' => now()]);
    }
}
