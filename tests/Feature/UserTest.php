<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_has_current_company_id()
    {
        $user = User::factory()->create(['current_company_id', 1]);

        $this->assertNotNull($user->current_company_id);
    }
}
