<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class inventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier',
        'item'
    ];

    protected $casts = [
        'item' => 'array'
    ];

    public function getItemCountAttribute()
    {
        return count($this->item);
    }

    public function item(): HasMany
    {
        return $this->hasMany(item::class);
    }
}
