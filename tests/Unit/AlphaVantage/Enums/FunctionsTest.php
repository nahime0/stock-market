<?php

use App\AlphaVantage\Enums\Functions;

test('it returns the correct jsonPrefix for daily', function () {
    $daily = Functions::DAILY;

    expect($daily->jsonKey(['symbol' => 'IBM']))->toBe('Time Series (Daily)');
});

test('it returns the correct jsonPrefix for intraDay', function() {
    $intraDay = Functions::INTRADAY;

    expect($intraDay->jsonKey(['symbol' => 'IBM', 'interval' => 'XYZ']))->toBe('Time Series (XYZ)');
});