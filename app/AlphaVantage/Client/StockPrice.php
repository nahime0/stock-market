<?php

declare(strict_types=1);

namespace App\AlphaVantage\Client;

use DateTime;
use Exception;

/**
 * A representation of a stock price in a precise moment in time.
 * This class will take in input the strings returned by the API and
 * convert them to float values.
 */
final readonly class StockPrice
{
    private DateTime $datetime;

    private float $open;

    private float $high;

    private float $low;

    private float $close;

    private int $volume;

    /**
     * @param  non-empty-string  $datetime
     * @param  non-empty-string  $open
     * @param  non-empty-string  $high
     * @param  non-empty-string  $low
     * @param  non-empty-string  $close
     * @param  non-empty-string  $volume
     * @throws Exception
     */
    public function __construct(
        string $datetime,
        string $open,
        string $high,
        string $low,
        string $close,
        string $volume,
    ) {
        assert($datetime !== '', 'Datetime must not be empty');
        assert($open !== '', 'Open must not be empty');
        assert($high !== '', 'High must not be empty');
        assert($low !== '', 'Low must not be empty');
        assert($close !== '', 'Close must not be empty');
        assert($volume !== '', 'Volume must not be empty');

        $this->datetime = new DateTime($datetime);
        $this->open = (float) $open;
        $this->high = (float) $high;
        $this->low = (float) $low;
        $this->close = (float) $close;
        $this->volume = (int) $volume;
    }

    public function datetime(): DateTime
    {
        return $this->datetime;
    }

    public function open(): float
    {
        return $this->open;
    }

    public function high(): float
    {
        return $this->high;
    }

    public function low(): float
    {
        return $this->low;
    }

    public function close(): float
    {
        return $this->close;
    }

    public function volume(): int
    {
        return $this->volume;
    }
}
