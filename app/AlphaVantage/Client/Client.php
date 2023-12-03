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
     * @param  non-empty-string  $apiKey
     * @param  non-empty-string  $apiUrl
     */
    public static function factory(string $apiKey, string $apiUrl): self
    {
        return new self($apiKey, $apiUrl);
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
     * @return Collection<string, array{
     *     "1. open": string,
     *     "2. high": string,
     *     "3. low": string,
     *     "4. close": string,
     *     "5. volume": string,
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
     * @param  non-empty-string  $symbol
     * @return Collection<string, array{
     *     "1. open": string,
     *     "2. high": string,
     *     "3. low": string,
     *     "4. close": string,
     *     "5. volume": string,
     * }>
     */
    public function intraDay(string $symbol): Collection
    {
        return $this->request(Functions::INTRADAY, [
            'symbol'   => $symbol,
            'interval' => '1min'
        ]);
    }

    /**
     * @return Collection<string, array{
     *     "1. open": string,
     *     "2. high": string,
     *     "3. low": string,
     *     "4. close": string,
     *     "5. volume": string,
     * }>
     */
    public function daily(string $symbol): Collection
    {
        return $this->request(Functions::DAILY, [
            'symbol' => $symbol,
        ]);
    }
}
