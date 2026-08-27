<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'owner_id',
    'managed_by_user_id',
    'title',
    'description',
    'category_id',
    'property_type',
    'rent_amount',
    'deposit_amount',
    'maintenance_amount',
    'currency_code',
    'bhk',
    'bedrooms',
    'bathrooms',
    'size_sqft',
    'furnishing',
    'floor_number',
    'total_floors',
    'parking_spots',
    'preferred_tenant',
    'available_from',
    'address_line1',
    'address_line2',
    'city_id',
    'locality_id',
    'pincode',
    'latitude',
    'longitude',
    'location_precision',
    'house_rules',
    'is_owner_verified',
    'is_agent_listing',
    'is_sponsored',
    'status',
    'rejection_reason',
    'published_at',
    'expires_at',
    'last_status_changed_at',
])]
class Property extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'rent_amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'maintenance_amount' => 'decimal:2',
            'size_sqft' => 'decimal:2',
            'latitude' => 'float',
            'longitude' => 'float',
            'is_owner_verified' => 'boolean',
            'is_agent_listing' => 'boolean',
            'is_sponsored' => 'boolean',
            'available_from' => 'date',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'last_status_changed_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by_user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(PropertyMedia::class);
    }
}
