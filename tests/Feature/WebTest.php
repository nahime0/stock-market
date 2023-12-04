<?php

it('home returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('dashboard returns a successful response', function () {
    $response = $this->get('/dashboard');

    $response->assertStatus(200);
});
