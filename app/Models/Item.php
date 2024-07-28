<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'quantity',
        'user_id'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('by_user', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }


    public function dish(): BelongsToMany
    {
        return $this->belongsToMany(dish::class);
    }

    public function inventory(): BelongsToMany
    {
        return $this->belongsToMany(inventory::class);
    }
}
