<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class dish extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'items',
        'price',
        'user_id'
    ];

    protected $casts = [
        'items' => 'array'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('by_user', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }


    public function sale(): BelongsToMany
    {
        return $this->belongsToMany(sale::class);
    }

    public function item(): HasMany
    {
        return $this->hasMany(item::class);
    }
}
