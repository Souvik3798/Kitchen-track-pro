<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class inventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier',
        'item',
        'user_id'
    ];

    protected $casts = [
        'item' => 'array'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('by_user', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }

    public function getItemCountAttribute()
    {
        return count($this->item);
    }

    public function item(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
