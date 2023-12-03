<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PriceResource;
use App\Models\Symbol;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function ticker(Symbol $symbol): JsonResponse
    {
        $lastTwoPrices = $symbol->prices()->orderBy('datetime', 'DESC')->take(2)->get();
        if ($lastTwoPrices->count() < 2) {
            return response()->json([
                'error' => 'Not enough data to calculate the ticker',
            ], 400);
        }

        $currentPrice = $lastTwoPrices->first();
        $previousPrice = $lastTwoPrices->last();

        if (empty($currentPrice) || empty($previousPrice)) {
            return response()->json([
                'error' => 'Not enough data to calculate the ticker',
            ], 400);
        }

        $ticker = [
            'current' => [
                (new PriceResource($currentPrice)),
            ],
            'previous' => [
                new PriceResource($previousPrice),
            ],
            'change' => sprintf('%.4f', $currentPrice->close - $previousPrice->close),
            'change_percent' => sprintf('%.4f', ($currentPrice->close - $previousPrice->close) / $previousPrice->close * 100),
        ];

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
