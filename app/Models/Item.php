<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'quantity',
    ];


    public function dish(): BelongsToMany
    {
        return $this->belongsToMany(dish::class);
    }

    public function inventory(): BelongsToMany
    {
        return $this->belongsToMany(inventory::class);
    }
}
