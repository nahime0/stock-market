<?php

// Architecture testing
use App\AlphaVantage\Client\Client;
use App\AlphaVantage\Client\StockPrice;
use App\AlphaVantage\Enums\Functions;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->httpClient = Mockery::mock(Factory::class);

    $this->alphaVantageClient = new App\AlphaVantage\Client\Client(
        $this->httpClient,
        'demo',
        'https://www.alphavantage.co/query'
    );
});

test('that use the right architecture', function () {
    expect(Client::class)
        ->toUseStrictTypes()
        ->toBeFinal()
        ->toBeReadonly()
        ->toExtendNothing()
        ->toImplementNothing()
        ->toHaveConstructor()
        ->toOnlyUse([
            Functions::class,
            Collection::class,
            Factory::class,
            StockPrice::class
        ]);
});

test('that process correcly the intraday', function () {
    $this->httpClient->shouldReceive('get')
        ->once()
        ->with('https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&apikey=demo&symbol=IBM&interval=1min')
        ->andReturn(
            Mockery::mock(\Illuminate\Http\Client\Response::class)
                ->shouldReceive('collect')
                ->once()
                ->with('Time Series (1min)')
                ->andReturn(
                    collect([
                        '2021-01-01 00:00:00' => [
                            '1. open'   => '100.1234',
                            '2. high'   => '101.1234',
                            '3. low'    => '100.1234',
                            '4. close'  => '101.1234',
                            '5. volume' => '200',
                        ],
                    ])
                )
                ->getMock()
        );

    $data = $this->alphaVantageClient->intraDay('IBM');

    expect($data)->toBeInstanceOf(Collection::class);

    $data->each(function ($stockPrice) {
        expect($stockPrice)->toBeInstanceOf(StockPrice::class);
    });
});

test('that process correctly the daily', function () {
    $this->httpClient->shouldReceive('get')
        ->once()
        ->with('https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&apikey=demo&symbol=IBM')
        ->andReturn(
            Mockery::mock(\Illuminate\Http\Client\Response::class)
                ->shouldReceive('collect')
                ->once()
                ->with('Time Series (Daily)')
                ->andReturn(
                    collect([
                        '2021-01-01' => [
                            '1. open'   => '100.1234',
                            '2. high'   => '101.1234',
                            '3. low'    => '100.1234',
                            '4. close'  => '101.1234',
                            '5. volume' => '200',
                        ],
                    ])
                )
                ->getMock()
        );

    $data = $this->alphaVantageClient->daily('IBM');

    expect($data)->toBeInstanceOf(Collection::class);

    $data->each(function ($stockPrice) {
        expect($stockPrice)->toBeInstanceOf(StockPrice::class);
    });
});