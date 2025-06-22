<?php

namespace Tests\Feature;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class AuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Config::set('jwt.algo', 'HS256');
        Config::set('jwt.secret', base64_encode(random_bytes(64)));
    }

    private function createUser(): TestResponse
    {
        return $this->post(route('authRegister'), [
            'name' => fake()->name(),
            'email' =>  fake()->unique()->safeEmail(),
            'password' => 'password',
        ]);
    }

    private function loginUser(): TestResponse
    {
        $response = $this->createUser();
        $data = json_decode($response->getContent(), true);

        return $this->post(route('authLogin'), [
            'email' => $data['user']['email'],
            'password' => 'password',
        ]);
    }

    private function verifyUser(): TestResponse
    {
        $response = $this->loginUser();
        $data = json_decode($response->getContent(), true);

        return $this->post(route('authVerify'), [], [
            'Authorization' => 'Bearer ' . $data['access_token'],
        ]);
    }

    public function test_create_new_user()
    {
        $response = $this->createUser();
        $this->assertStringContainsString('User successfully registered"', $response->content());
        $this->assertJson($response->content());
        $response->assertStatus(200);
    }

    public function test_login_new_user()
    {
        $response = $this->loginUser();
        $response->assertStatus(200);
        $this->assertJson($response->content());

        $this->assertStringContainsString('access_token', $response->content());
        $this->assertStringContainsString('bearer', $response->content());
        $this->assertStringContainsString('expires_in', $response->content());

    }

    public function test_verify_new_user()
    {
        $response = $this->verifyUser();
        $response->assertStatus(200);
        $responseContent = $response->getContent();

        $this->assertStringContainsString('name', $responseContent);
        $this->assertStringContainsString('email',$responseContent);
        $this->assertStringContainsString('roles', $responseContent);
    }

    public function test_get_user_id()
    {
        $loginUser = $this->loginUser();
        $loginData = json_decode($loginUser->getContent(), true);

        $verifyData = $this->verifyUser();
        $data = json_decode($verifyData->getContent(), true);

        $response = $this->get(route('authUserById',['id' => $data['id']]), [
            'Authorization' => 'Bearer ' . $loginData['access_token'],
        ]);

        $response->assertStatus(200);
        $this->assertJson($response->content());
        $this->assertStringContainsString('User successfully retrieved', $response->content());
    }

    public function test_verify_refresh_token()
    {
        $response = $this->loginUser();
        $data = json_decode($response->getContent(), true);

        $response = $this->post(route('authRefresh'), [], [
            'Authorization' => 'Bearer ' . $data['access_token'],
        ]);

        $response->assertStatus(200);
        $this->assertJson($response->content());
        $responseContent = $response->content();

        $this->assertStringContainsString('access_token', $responseContent);
        $this->assertStringContainsString('bearer',$responseContent);
        $this->assertStringContainsString('expires_in', $responseContent);
    }

    public function test_user_logout()
    {
        $response = $this->loginUser();
        $data = json_decode($response->getContent(), true);
        $response = $this->post(route('authLogout'), [
            'Authorization' => 'Bearer ' . $data['access_token'],
        ]);

        $response->assertStatus(200);
        $this->assertJson($response->content());
        $this->assertStringContainsString('Successfully logged out', $response->content());
    }

    public function test_verify_user_with_wrong_bearer_token()
    {
        $response = $this->post(route('authVerify'), [], [
            'Authorization' => 'Bearer 1234567890',
        ]);

        $response->assertStatus(401);
        $this->assertJson($response->content());
        $this->assertStringContainsString('Unauthorized', $response->content());
    }
}
