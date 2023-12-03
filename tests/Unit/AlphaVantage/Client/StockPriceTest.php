<?php

use App\AlphaVantage\Client\StockPrice;

beforeEach(function() {
    $this->datatime = (new DateTime())->format("Y-m-d H:i:s");
    $this->open = "100.1234";
    $this->high = "101.1234";
    $this->low = "100.1234";
    $this->close = "101.1234";
    $this->volume = "200";

    $this->stockPrice = new StockPrice(
        $this->datatime,
        $this->open,
        $this->high,
        $this->low,
        $this->close,
        $this->volume,
    );
});

// Architecture testing
test('that use the right architecture', function() {
    expect(StockPrice::class)
        ->toUseStrictTypes()
        ->toBeFinal()
        ->toBeReadonly()
        ->toUseNothing()
        ->toExtendNothing()
        ->toImplementNothing()
        ->toHaveConstructor()
    ;
});

test('that datetime is untouched', function () {
    expect($this->stockPrice->datetime())->toBe($this->datatime);
});

test('that open is converted to float', function () {
    expect($this->stockPrice->open())->toBe(100.1234);
});

test('that high is converted to float', function () {
    expect($this->stockPrice->high())->toBe(101.1234);
});

test('that low is converted to float', function () {
    expect($this->stockPrice->low())->toBe(100.1234);
});

test('that close is converted to float', function () {
    expect($this->stockPrice->close())->toBe(101.1234);
});

test('that volume is converted to int', function () {
    expect($this->stockPrice->volume())->toBe(200);
});

