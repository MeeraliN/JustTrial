<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'state_name', 'country_name', 'is_active'])]
class City extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
