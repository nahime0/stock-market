<?php

use App\Models\Price;
use App\Models\Symbol;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->seed();
});

it('returns the list of symbols', function () {
    $response = $this->get('/api/symbols');

    $response->assertStatus(200);
    $response->assertJson(fn (AssertableJson $json) => $json->has(10)->first(fn (AssertableJson $json) => $json
        ->hasAll('symbol', 'name')
        ->whereAllType([
            'symbol' => 'string',
            'name' => 'string',
        ])
    ));
});

it('returns the ticker', function () {
    $symbol = Symbol::first();

    $response = $this->get("/api/ticker/{$symbol->symbol}");
    $response->assertStatus(200);

    $response->assertJson(fn (AssertableJson $json) => $json
        ->hasAll('current', 'previous', 'change', 'change_percent')
        ->whereAllType([
            'current' => 'array',
            'previous' => 'array',
            'change' => 'string',
            'change_percent' => 'string',
        ])
        ->hasAll('current.datetime', 'current.open', 'current.high', 'current.low', 'current.close', 'current.volume')
        ->hasAll('previous.datetime', 'previous.open', 'previous.high', 'previous.low', 'previous.close', 'previous.volume')
        ->whereAllType([
            'current.datetime' => 'string',
            'current.open' => 'string',
            'current.high' => 'string',
            'current.low' => 'string',
            'current.close' => 'string',
            'current.volume' => 'string',
            'previous.datetime' => 'string',
            'previous.open' => 'string',
            'previous.high' => 'string',
            'previous.low' => 'string',
            'previous.close' => 'string',
            'previous.volume' => 'string',
        ])
    );
});

it('will cache the ticker response', function () {
    $symbol = Symbol::first();

    $response1 = $this->get("/api/ticker/{$symbol->symbol}");
    $response1->assertStatus(200);

    Price::create([
        'symbol_id' => $symbol->id,
        'datetime' => (new DateTime())->modify('+1 day'),
        'open' => 1,
        'high' => 1,
        'low' => 1,
        'close' => 1,
        'volume' => 1,
    ]);

    $response2 = $this->get("/api/ticker/{$symbol->symbol}");
    $response2->assertStatus(200);

    assert($response1->json('current.datetime') === $response2->json('current.datetime'));
});

it('returns the price history', function () {
    $symbol = Symbol::first();

    $response = $this->get("/api/history/{$symbol->symbol}");
    $response->assertStatus(200);

    $response->assertJson(fn (AssertableJson $json) => $json
        ->hasAll([
            'data',
            'data.0',
            'data.0.datetime',
            'data.0.open',
            'data.0.high',
            'data.0.low',
            'data.0.close',
            'data.0.volume',
            'links',
            'meta',
        ])
        ->whereAllType([
            'data.0.datetime' => 'string',
            'data.0.open' => 'string',
            'data.0.high' => 'string',
            'data.0.low' => 'string',
            'data.0.close' => 'string',
            'data.0.volume' => 'string',
        ])
    );
});
