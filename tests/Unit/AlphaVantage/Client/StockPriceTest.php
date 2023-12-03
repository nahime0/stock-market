<?php

use App\AlphaVantage\Client\StockPrice;

beforeEach(function () {
    $this->datetime = (new DateTime())->format('Y-m-d H:i:s');
    $this->open = '100.1234';
    $this->high = '101.1234';
    $this->low = '100.1234';
    $this->close = '101.1234';
    $this->volume = '200';

    $this->stockPrice = new StockPrice(
        $this->datetime,
        $this->open,
        $this->high,
        $this->low,
        $this->close,
        $this->volume,
    );
});

// Architecture testing
test('uses the right architecture', function () {
    expect(StockPrice::class)
        ->toUseStrictTypes()
        ->toBeFinal()
        ->toBeReadonly()
        ->toUseNothing()
        ->toExtendNothing()
        ->toImplementNothing()
        ->toHaveConstructor();
});

test('datetime is converted', function () {
    expect($this->stockPrice->datetime())->toBeInstanceOf(DateTime::class)
        ->and($this->stockPrice->datetime()->format('Y-m-d H:i:s'))->toBe($this->datetime);
});

test('open is converted to float', function () {
    expect($this->stockPrice->open())->toBe(100.1234);
});

test('high is converted to float', function () {
    expect($this->stockPrice->high())->toBe(101.1234);
});

test('low is converted to float', function () {
    expect($this->stockPrice->low())->toBe(100.1234);
});

test('close is converted to float', function () {
    expect($this->stockPrice->close())->toBe(101.1234);
});

test('volume is converted to int', function () {
    expect($this->stockPrice->volume())->toBe(200);
});
