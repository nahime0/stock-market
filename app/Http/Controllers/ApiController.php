<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PriceResource;
use App\Http\Resources\SymbolResource;
use App\Models\Symbol;
use Cache;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function symbols(): JsonResponse
    {
        return response()->json(
            SymbolResource::collection(Symbol::all())
        );
    }

    public function ticker(Symbol $symbol): JsonResponse
    {
        $ttl = config('stock_market.cache_ttl', 60);

        assert(is_string($ttl) || is_int($ttl), 'Cache TTL must be a string or integer.');

        $ttl = (int) $ttl;

        assert($ttl > 0, 'Cache TTL must be a positive integer.');

        $ticker = Cache::remember(
            "ticker.{$symbol->symbol}",
            $ttl,
            fn () => $symbol->ticker()
        );

        if (! $ticker) {
            return response()->json([
                'message' => 'Not enough data to calculate ticker.',
            ], 400);
        }

        $ticker['current'] = new PriceResource($ticker['current']);
        $ticker['previous'] = new PriceResource($ticker['previous']);
        $ticker['change'] = sprintf('%.4f', $ticker['change']);
        $ticker['change_percent'] = sprintf('%.4f', $ticker['change_percent']);

        return response()->json($ticker);
    }

    public function history(Symbol $symbol): JsonResponse
    {

        $data = $symbol->prices()->orderBy('datetime', 'DESC')->paginate(10);

        return response()->json(
            PriceResource::collection($data)->response()->getData(true)
        );
    }
}
