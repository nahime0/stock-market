<?php

namespace Database\Seeders;

use App\Models\Price;
use App\Models\Symbol;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Symbol::all()->each(function (Symbol $symbol) {
            Price::factory(100)->create([
                'symbol_id' => $symbol->id,
            ]);
        });
    }
}
