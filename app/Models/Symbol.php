<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Symbol extends Model
{
    use HasFactory;

    /**
     * @return HasMany<Price>
     */
    protected $fillable = [
        'symbol',
        'name',
    ];

    /**
     * @return HasMany<Price>
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }
}
