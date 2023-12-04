<?php

return [
    'cache_ttl' => env('STOCK_MARKET_CACHE_TTL', 60),
    'alpha_vantage' => [
        'api_key' => env('STOCK_MARKET_ALPHA_VANTAGE_API_KEY'),
        'api_url' => env('STOCK_MARKET_ALPHA_VANTAGE_API_URL'),
    ],
];
