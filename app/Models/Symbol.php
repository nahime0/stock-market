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

    /**
     * This function will return the ticker (current price, previous price, change, change percent) for the symbol.
     *
     * @return null|array{current: Price, previous: Price, change: float, change_percent: float}
     */
    public function ticker(): ?array
    {
        $lastTwoPrices = $this->prices()->orderBy('datetime', 'DESC')->take(2)->get();

        if ($lastTwoPrices->count() < 2) {
            return null;
        }

        $currentPrice = $lastTwoPrices->first();
        $previousPrice = $lastTwoPrices->last();

        if (empty($currentPrice) || empty($previousPrice)) {
            return null;
        }

        return [
            'current' => $currentPrice,
            'previous' => $previousPrice,
            'change' => $currentPrice->close - $previousPrice->close,
            'change_percent' => ($currentPrice->close - $previousPrice->close) / $previousPrice->close * 100,
        ];
    }
}
