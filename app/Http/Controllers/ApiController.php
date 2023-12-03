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

    public function pricing(Symbol $symbol): JsonResponse
    {

        $data = $symbol->prices()->orderBy('datetime', 'DESC')->paginate(10);

        return response()->json(
            PriceResource::collection($data)->response()->getData(true)
        );
    }
}
