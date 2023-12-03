<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Symbol
 */
class SymbolResource extends JsonResource
{
    /**
     * @return array{symbol: string, name: string}
     */
    public function toArray(Request $request): array
    {
        return [
            'symbol' => $this->symbol,
            'name' => $this->name,
        ];
    }
}
