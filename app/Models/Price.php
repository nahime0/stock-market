<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory;

    protected $casts = [
        'datetime' => 'datetime',
        'open' => 'float',
        'high' => 'float',
        'low' => 'float',
        'close' => 'float',
    ];

    /**
     * @return BelongsTo<Symbol, Price>
     */
    public function symbol(): BelongsTo
    {
        return $this->belongsTo(Symbol::class);
    }
}
