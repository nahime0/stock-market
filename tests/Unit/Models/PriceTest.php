<?php

use App\Models\Symbol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('it has correct architecture', function() {
    expect(\App\Models\Price::class)
        ->toUseStrictTypes()
        ->toOnlyUse([
            Model::class,
            HasFactory::class,
            BelongsTo::class,
            Symbol::class
        ])
        ;
});