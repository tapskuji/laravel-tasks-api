<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_login_with_correct_credentials_and_receives_auth_token()
    {
        $password = '1234567890';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson(route('users.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $content =  $response->getContent();
        $jsonData = json_decode($content);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'token_type',
                'expires_in',
                'access_token',
            ]
        ]);
        $this->assertIsString($jsonData->data->access_token);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('users.login'), [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
