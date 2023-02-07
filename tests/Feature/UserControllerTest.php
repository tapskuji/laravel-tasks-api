<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_their_data()
    {
        $user = User::factory()->create();

        $expected = [
            'id' => (string)$user->id,
            'name' => (string)$user->name,
            'email' => (string)$user->email,
            'createdAt' => (string)$user->created_at,
            'updatedAt' => (string)$user->updated_at,
        ];

        $response = $this->actingAs($user)->getJson(route('users.read'));
        $content =  $response->getContent();
        $jsonData = json_decode($content);
        $actual = (array)$jsonData->data;

        $response->assertOk();
        $this->assertSame($expected, $actual);
    }

    public function test_authenticated_user_can_update_their_data()
    {
        $originalUserInfo = [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'password',
        ];

        $user = User::factory()->create($originalUserInfo);

        $updateData = [
            'name' => 'New name',
            'email' => 'newemail@exaample.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];

        $response = $this->actingAs($user)->putJson(route('users.update'), $updateData);

        $content =  $response->getContent();
        $jsonData = json_decode($content);
        $actual = (array)$jsonData->data;

        $actualData = [
            'id' => (string)$actual['id'],
            'name' => $updateData['name'],
            'email' => $updateData['email'],
        ];

        $expected = [
            'id' => (string)$user->id,
            'name' => $updateData['name'],
            'email' => $updateData['email'],
        ];

        $user = User::find($user->id)->first();

        $response->assertOk();
        $this->assertSame($expected, $actualData);
        $this->assertTrue(Hash::check($updateData['password'], $user->password));

    }
}
