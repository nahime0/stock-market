<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Symbol;
use Illuminate\Console\Command;

class FillSymbols extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-symbols';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // 1. Apple Inc. (AAPL)
        Symbol::updateOrCreate([
            'symbol' => 'AAPL',
        ], [
            'name' => 'Apple Inc.',
        ]);

        // 2. Microsoft Corporation (MSFT)
        Symbol::updateOrCreate([
            'symbol' => 'MSFT',
        ], [
            'name' => 'Microsoft Corporation',
        ]);

        // 3. Alphabet Inc. (GOOG)
        Symbol::updateOrCreate([
            'symbol' => 'GOOG',
        ], [
            'name' => 'Alphabet Inc.',
        ]);

        // 4. Amazon.com, Inc. (AMZN)
        Symbol::updateOrCreate([
            'symbol' => 'AMZN',
        ], [
            'name' => 'Amazon.com, Inc.',
        ]);

        // 5. Meta Platforms, Inc. (META)
        Symbol::updateOrCreate([
            'symbol' => 'META',
        ], [
            'name' => 'Meta Platforms, Inc.',
        ]);

        // 6. NVIDIA Corporation (NVDA)
        Symbol::updateOrCreate([
            'symbol' => 'NVDA',
        ], [
            'name' => 'NVIDIA Corporation',
        ]);

        // 7. Tesla, Inc. (TSLA)
        Symbol::updateOrCreate([
            'symbol' => 'TSLA',
        ], [
            'name' => 'Tesla, Inc.',
        ]);

        // 8. Oracle Corporation (ORCL)
        Symbol::updateOrCreate([
            'symbol' => 'ORCL',
        ], [
            'name' => 'Oracle Corporation',
        ]);

        // 9. Johnson & Johnson (JNJ)
        Symbol::updateOrCreate([
            'symbol' => 'JNJ',
        ], [
            'name' => 'Johnson & Johnson',
        ]);

        // 10. Procter & Gamble Company (PG)
        Symbol::updateOrCreate([
            'symbol' => 'PG',
        ], [
            'name' => 'Procter & Gamble Company',
        ]);

        $this->info('Correctly filled default symbols');
    }
}
