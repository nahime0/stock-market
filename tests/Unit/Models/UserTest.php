<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as LaravelUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

test('it has correct architecture', function () {
    expect(User::class)
        ->toUseStrictTypes()
        ->toOnlyUse([
            LaravelUser::class,
            Notifiable::class,
            HasFactory::class,
            HasApiTokens::class,
        ]);
});
