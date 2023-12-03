<?php

declare(strict_types=1);

namespace App\AlphaVantage;

use Illuminate\Support\Facades\Facade;

class AlphaVantage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client\Client::class;
    }
}
