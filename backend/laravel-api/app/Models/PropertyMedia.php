<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'property_id',
    'media_type',
    'original_path',
    'thumbnail_path',
    'mime_type',
    'file_size_bytes',
    'youtube_url',
    'is_cover',
    'sort_order',
])]
class PropertyMedia extends Model
{
    protected function casts(): array
    {
        return [
            'is_cover' => 'boolean',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
