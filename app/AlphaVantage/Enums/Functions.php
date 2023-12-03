<?php

declare(strict_types=1);

namespace App\AlphaVantage\Enums;

enum Functions: string
{
    case INTRADAY = 'TIME_SERIES_INTRADAY';
    case DAILY = 'TIME_SERIES_DAILY';

    /**
     * @param  array{symbol: string, interval?: string}  $options
     */
    public function jsonKey(array $options): string
    {
        return match ($this) {
            self::INTRADAY => sprintf('Time Series (%s)', $options['interval'] ?? '1min'),
            self::DAILY => 'Time Series (Daily)',
        };
    }
}
