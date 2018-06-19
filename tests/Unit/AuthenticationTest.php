<?php

namespace Tests\Unit;

use Auth;
use Tests\PassportTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Inggo\Boilerplate\User;

class AuthenticationTest extends PassportTestCase
{
    use RefreshDatabase;

    private $dummy_data = [
        'username' => 'dummy',
        'email' => 'dummy@test.com',
        'password' => 'abcd1234'
    ];

    public function testLogout()
    {
        $response = $this->json('GET', '/logout/');
        
        $response->assertStatus(302);
        
        $this->assertNull(Auth::user());
    }

    public function testGETNotAllowed()
    {
        $response = $this->json('GET', '/login');
        
        $response->assertStatus(405);
    }

    public function testNoData()
    {
        $response = $this->json('POST', '/login', []);
        
        $response->assertStatus(422);
    }

    public function testInvalidData()
    {
        $response = $this->json('POST', '/login', $this->dummy_data);
        
        $response->assertStatus(422);
    }

    private function setupUser()
    {
        $this->user = factory(User::class)->create([
            'username' => $this->dummy_data['username'],
            'email' => $this->dummy_data['email'],
            'password' => bcrypt($this->dummy_data['password'])
        ]);
    }

    private function setupUserAndLogin()
    {
        $this->setupUser();

        return $this->json('POST', '/login', $this->dummy_data);
    }

    public function testValidData()
    {
        $response = $this->setupUserAndLogin();

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token'
            ]);

        $this->assertNotNull(Auth::user());
    }

    public function testLogoutAuthenticated()
    {
        $this->setupUserAndLogin();

        $this->assertNotNull(Auth::user());

        $response = $this->json('GET', '/logout/');

        $response->assertStatus(302);

        $this->assertNull(Auth::user());
    }
}
