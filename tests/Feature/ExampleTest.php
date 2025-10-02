<?php

use App\Models\User;
use function Pest\Laravel\{assertDatabaseCount};


it('example', function () {
    assertDatabaseCount('users', 0);

    // começa vazio
    User::factory()->count(2)->create();

    assertDatabaseCount('users', 2);
    // agora deve ter 2
});
