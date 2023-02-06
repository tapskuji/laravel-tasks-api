<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_has_expected_table_users_and_columns()
    {
        $this->assertTrue(Schema::hasTable('users'), 'Users table is missing');

        $this->assertTrue(
            Schema::hasColumns('users', [
                'id','name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at',
            ]),
            'Users table is missing some columns'
        );
    }

    public function test_user_email_is_stored_in_lower_case()
    {
        $email = 'TEST@EXAMPLE.COM';

        $user = User::create([
            'name' => 'Test',
            'email' => $email,
            'password' => '123',
        ]);

        $this->assertEquals(strtolower($email), $user->email);
    }

    public function test_user_email_must_be_unique()
    {
        $this->expectException(QueryException::class);

        $users = User::factory()->count(2)->create([
            'email' => 'test@example.com'
        ]);
    }
}
