<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{
    public function test_example()
    {
        $this->assertDatabaseCount('users', 0); // começa vazio

        User::factory()->count(2)->create();

        $this->assertDatabaseCount('users', 2); // agora deve ter 2
    }
}
