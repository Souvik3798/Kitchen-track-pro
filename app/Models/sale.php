<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class sale extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $fillable = [
        'dish',
        'customer',
    ];

    protected $casts = [
        'dish' => 'array'
    ];
    public function dish(): HasMany
    {
        return $this->hasMany(dish::class, 'id', 'dish_id');
    }

    public function item(): HasManyThrough
    {
        return $this->hasManyThrough(item::class, dish::class, 'id', 'dish_id');
    }

    public function getItemCountAttribute()
    {
        return count($this->dish);
    }
}
