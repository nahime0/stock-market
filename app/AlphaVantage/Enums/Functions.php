<?php

namespace App\AlphaVantage\Enums;

enum Functions: string {
    case INTRADAY = 'TIME_SERIES_INTRADAY';
    case DAILY = 'TIME_SERIES_DAILY';
}