<?php

use App\Models\Price;
use App\Models\Symbol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('it has correct architecture', function() {
    expect(Symbol::class)
        ->toUseStrictTypes()
        ->toOnlyUse([
            Model::class,
            HasFactory::class,
            HasMany::class,
            Price::class
        ])
    ;
});