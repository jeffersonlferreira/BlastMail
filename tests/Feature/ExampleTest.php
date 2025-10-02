<?php

use App\Models\User;


test('example', function () {
    $this->assertDatabaseCount('users', 0);

    // começa vazio
    User::factory()->count(2)->create();

    $this->assertDatabaseCount('users', 2);
    // agora deve ter 2
});
