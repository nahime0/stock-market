<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Price
 */
class PriceResource extends JsonResource
{
    /**
     * @return array{datetime: string, open: float, high: float, low: float, close: float, volume: int}
     */
    public function toArray(Request $request): array
    {
        return [
            'datetime' => $this->datetime->format('Y-m-d H:i:s'),
            'open' => sprintf("%.4f", $this->open),
            'high' => sprintf("%.4f", $this->high),
            'low' => sprintf("%.4f", $this->low),
            'close' => sprintf("%.4f", $this->close),
            'volume' => (string)$this->volume,
        ];
    }
}
