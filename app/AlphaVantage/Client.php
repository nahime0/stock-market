<?php

namespace App\AlphaVantage;

use App\AlphaVantage\Enums\Functions;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client
{
    private string $apiKey;

    private string $apiUrl;

    /**
     * @param  non-empty-string  $apiKey
     * @param  non-empty-string  $apiUrl
     */
    public function __construct(string $apiKey, string $apiUrl)
    {
        assert($apiKey !== '', 'API key must not be empty');
        assert($apiUrl !== '', 'API URL must not be empty');

        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }

    /**
     * @param  non-empty-string  $apiKey
     * @param  non-empty-string  $apiUrl
     */
    public static function factory(string $apiKey, string $apiUrl): self
    {
        assert($apiKey !== '', 'API key must not be empty');
        assert($apiUrl !== '', 'API URL must not be empty');

        return new self($apiKey, $apiUrl);
    }

    /**
     * @param  array<string, string>  $options
     */
    private function buildUrl(Functions $function, array $options = []): string
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
     * @param  array<string, string>  $options
     */
    private function request(Functions $function, array $options = []): Response|PromiseInterface
    {
        return Http::get(
            $this->buildUrl($function, $options)
        );
    }

    /**
     * @param  non-empty-string  $symbol
     */
    public function intraDay(string $symbol): PromiseInterface|Response
    {
        return $this->request(Functions::INTRADAY, [
            'symbol' => $symbol,
            'interval' => '1min'
        ]);
    }
}
