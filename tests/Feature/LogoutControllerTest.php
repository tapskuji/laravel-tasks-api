<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_user()
    {
        $password = '1234567890';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson(route('users.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $content =  $response->json();
        $token = $content['data']['access_token'];

        $this->assertIsString($token);
        $this->assertEquals(1, DB::table('personal_access_tokens')->count());

        $headers = ['Authorization' => 'Bearer ' . $token];
        $response = $this->postJson(route('users.logout'), [], $headers);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        DB::table('personal_access_tokens')->get();
        $this->assertEquals(0, DB::table('personal_access_tokens')->count());
    }
}
