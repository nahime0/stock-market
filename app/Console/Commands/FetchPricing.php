<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\AlphaVantage\AlphaVantage;
use App\AlphaVantage\Client\StockPrice;
use App\Exceptions\RemoteApiNotAvailable;
use App\Models\Price;
use App\Models\Symbol;
use Illuminate\Console\Command;

class FetchPricing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-pricing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch pricing for all symbols';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Symbol::all()->each(function (Symbol $symbol) {
            if (! empty($symbol->symbol)) {
                try {
                    AlphaVantage::intraDay($symbol->symbol)->each(function (StockPrice $price) use ($symbol) {
                        /**
                         * If we already have a price for a symbol and a datetime, update it.
                         * It nothing has changed the state will remain the same.
                         */
                        Price::updateOrCreate([
                            'symbol_id' => $symbol->id,
                            'datetime' => $price->datetime(),
                        ], [
                            'open' => $price->open(),
                            'high' => $price->high(),
                            'low' => $price->low(),
                            'close' => $price->close(),
                            'volume' => $price->volume(),
                        ]);
                    });

                    $this->info("Fetched pricing for {$symbol->name}");
                } catch (RemoteApiNotAvailable) {
                    $this->error("Remote API not available for {$symbol->name}");
                }
            } else {
                $this->error("Symbol {$symbol->name} does not have a symbol");
            }
        });
    }
}
