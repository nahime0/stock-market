<?php

declare(strict_types=1);

namespace App\AlphaVantage\Client;

use App\AlphaVantage\Enums\Functions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final readonly class Client
{
    /**
     * @param  non-empty-string  $apiKey
     * @param  non-empty-string  $apiUrl
     */
    public function __construct(
        private string $apiKey,
        private string $apiUrl
    ) {
        assert($apiKey !== '', 'API key must not be empty');
        assert($apiUrl !== '', 'API URL must not be empty');
    }

    /**
     * @param  array{symbol: string, interval?: string}  $options
     */
    private function buildUrl(Functions $function, array $options): string
    {
        $url = sprintf('%s?function=%s&apikey=%s', $this->apiUrl, $function->value, $this->apiKey);
        return array_reduce(
            array_keys($options),
            function (string $url, string $key) use ($options) {
                return sprintf('%s&%s=%s', $url, $key, $options[$key]);
            },
            $url
        );
    }

    /**
     * @param  array{symbol: string, interval?: string}  $options
     * @return Collection<non-empty-string, array{
     *     "1. open": non-empty-string,
     *     "2. high": non-empty-string,
     *     "3. low": non-empty-string,
     *     "4. close": non-empty-string,
     *     "5. volume": non-empty-string,
     * }>
     */
    private function request(Functions $function, array $options): Collection
    {
        $response = Http::get(
            $this->buildUrl($function, $options)
        );

        return $response->collect($function->jsonKey($options));
    }

    /**
     * @param Collection<non-empty-string, array{
     *     "1. open": non-empty-string,
     *     "2. high": non-empty-string,
     *     "3. low": non-empty-string,
     *     "4. close": non-empty-string,
     *     "5. volume": non-empty-string,
     * }> $data
     * @return Collection<int, StockPrice>
     */
    private function transformResponse(Collection $data): Collection
    {
        return $data->map(function(array $data, string $datetime) {
            return new StockPrice(
                $datetime,
                $data['1. open'],
                $data['2. high'],
                $data['3. low'],
                $data['4. close'],
                $data['5. volume'],
            );
        })->values();
    }

    /**
     * @param  non-empty-string  $symbol
     * @return Collection<int, StockPrice>
     */
    public function intraDay(string $symbol): Collection
    {
        $data = $this->request(Functions::INTRADAY, [
            'symbol'   => $symbol,
            'interval' => '1min'
        ]);

        return $this->transformResponse($data);
    }

    /**
     * @return Collection<int, StockPrice>
     */
    public function daily(string $symbol): Collection
    {
        $data = $this->request(Functions::DAILY, [
            'symbol' => $symbol,
        ]);

        return $this->transformResponse($data);
    }
}
