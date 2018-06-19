<?php

namespace Tests\Unit;

use Tests\PassportAuthenticatedTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Inggo\Boilerplate\User;

class UsersApiStoreTest extends PassportAuthenticatedTestCase
{
    use RefreshDatabase;

    private $test_user_data = [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'testuser@example.com',
        'password' => 'testing',
        'password_confirmation' => 'testing',
        'role' => 'user',
    ];

    private function postStoreUser()
    {
        return $this->post('/api/users/', $this->test_user_data);
    }

    private function encryptedUserData()
    {
        $encrypted_data = $this->test_user_data;

        unset($encrypted_data['password']);
        unset($encrypted_data['password_confirmation']);

        return $encrypted_data;
    }

    public function testStoreFailsForNonJSON()
    {
        $this->headers = [];

        $this->performStoreChecking(true, 400);
    }

    public function testStoreFailsWhenLoggedOut()
    {
        unset($this->headers['Authorization']);

        $this->performStoreChecking(true, 401);
    }

    private function performStoreChecking($failing = false, $failing_status = 403)
    {
        $response = $this->postStoreUser();

        if ($failing) {
            $response->assertStatus($failing_status);
            return $response;
        }

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $this->test_user_data['name'],
                    'username' => $this->test_user_data['username'],
                    'email' => $this->test_user_data['email']
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    'password'
                ]
            ]);
        
        $this->assertDatabaseHas('users', $this->encryptedUserData());

        return $response;
    }

    public function testOwnersCanStore()
    {
        $this->setRoleTo('owner');

        $this->performStoreChecking();
    }

    public function testAdminsCanStore()
    {
        $this->setRoleTo('admin');

        $this->performStoreChecking();
    }

    public function testAdminsCannotStoreAdminsOrOwners()
    {
        $this->setRoleTo('admin');

        $this->test_user_data['role'] = 'owner';

        $this->performStoreChecking(true, 422);

        $this->test_user_data['role'] = 'admin';

        $this->performStoreChecking(true, 422);

        $this->test_user_data['role'] = 'manager';
        
        $this->performStoreChecking();
    }
        
    public function testManagersCantStore()
    {
        $this->setRoleTo('manager');

        $this->performStoreChecking(true);
    }

    public function testUsersCantStore()
    {
        $this->setRoleTo('user');

        $this->performStoreChecking(true);
    }

    public function testDuplicate()
    {
        $this->setRoleTo('admin');

        $this->performStoreChecking();

        $this->performStoreChecking(true, 422);
    }

    public function testInvalidEmail()
    {
        $this->setRoleTo('owner');

        $this->test_user_data['email'] = 'invalid-email-address';

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'email' => [
                    0 => 'The email must be a valid email address.'
                ]
            ]
        ]);
    }

    public function testInvalidUsername()
    {
        $this->setRoleTo('owner');

        $this->test_user_data['username'] = '+ invalid @ username +';

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'username' => [
                    0 => 'The username may only contain letters, numbers, and dashes.'
                ]
            ]
        ]);
    }

    public function testNoEmail()
    {
        $this->setRoleTo('owner');

        unset($this->test_user_data['email']);

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'email' => [
                    0 => 'The email field is required.'
                ]
            ]
        ]);
    }

    public function testInvalidName()
    {
        $this->setRoleTo('owner');

        $this->test_user_data['name'] = 'a';

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'name' => [
                    0 => 'The name must be at least 4 characters.'
                ]
            ]
        ]);
    }

    public function testNoName()
    {
        $this->setRoleTo('owner');

        unset($this->test_user_data['name']);

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'name' => [
                    0 => 'The name field is required.'
                ]
            ]
        ]);
    }

    public function testInvalidPassword()
    {
        $this->setRoleTo('owner');

        $this->test_user_data['password'] = 'a';
        $this->test_user_data['password_confirmation'] = 'a';

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'password' => [
                    0 => 'The password must be at least 6 characters.'
                ]
            ]
        ]);
    }

    public function testNoPassword()
    {
        $this->setRoleTo('owner');

        unset($this->test_user_data['password']);

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'password' => [
                    0 => 'The password field is required.'
                ]
            ]
        ]);
    }

    public function testPasswordMismatch()
    {
        $this->setRoleTo('owner');

        $this->test_user_data['password_confirmation'] = 'abcd';

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'password' => [
                    0 => 'The password confirmation does not match.'
                ]
            ]
        ]);
    }

    public function testInvalidRole()
    {
        $this->setRoleTo('owner');

        $this->test_user_data['role'] = 'a';

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'role' => [
                    0 => 'The selected role is invalid.'
                ]
            ]
        ]);
    }

    public function testNoRole()
    {
        $this->setRoleTo('owner');

        unset($this->test_user_data['role']);

        $response = $this->performStoreChecking(true, 422);

        $response->assertJson([
            'errors' => [
                'role' => [
                    0 => 'The role field is required.'
                ]
            ]
        ]);
    }
}
