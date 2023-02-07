<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register()
    {
        $userData = [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('users.register'), $userData);
        $content =  $response->getContent();
        $jsonData = json_decode($content);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals($userData['name'], $jsonData->data->name);
        $this->assertEquals($userData['email'], $jsonData->data->email);
    }

    public function test_cannot_register_without_required_information()
    {
        $response = $this->postJson(route('users.register'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'email',
                'password',
            ]
        ]);
    }
}
