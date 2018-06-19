<?php

namespace Tests\Unit;

use Tests\PassportAuthenticatedTestCase;
use Tests\Traits\CreatesTestUsers;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inggo\Boilerplate\User;

class UsersApiUpdateTest extends PassportAuthenticatedTestCase
{
    use RefreshDatabase;
    use CreatesTestUsers;

    private $update_data = [
        'username' => 'updatedusername',
        'email' => 'updatedemail@example.com',
        'name' => 'Updated Name',
        'role' => 'user'
    ];

    private function putUser($user, $update_data)
    {
        return $this->put("/api/users/{$user->id}", $update_data);
    }

    private function performUpdateChecking($user, $failing = false, $failing_status = 403)
    {
        $response = $this->putUser($user, $this->update_data);

        if ($failing) {
            $response->assertStatus($failing_status);
            return $response;
        }

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => $this->update_data['name'],
                    'username' => $this->update_data['username'],
                    'email' => $this->update_data['email']
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    'password'
                ]
            ]);

        $this->assertDatabaseHas('users', $this->update_data);

        return $response;
    }

    public function testUpdateFailsForNonJSON()
    {
        $this->headers = [];

        $this->performUpdateChecking($this->users['user'], true, 400);
    }

    public function testUpdateFailsWhenLoggedOut()
    {
        unset($this->headers['Authorization']);

        $this->performUpdateChecking($this->users['user'], true, 401);
    }

    public function testOwnersCanUpdateUsers()
    {
        $this->setRoleTo('owner');

        $this->performUpdateChecking($this->users['user']);
    }

    public function testOwnersCanUpdateManagers()
    {
        $this->setRoleTo('owner');

        $this->performUpdateChecking($this->users['manager']);
    }

    public function testOwnersCanUpdateAdmins()
    {
        $this->setRoleTo('owner');

        $this->update_data['role'] = 'manager';

        $this->performUpdateChecking($this->users['admin']);
    }

    public function testOwnersCannotUpdateOwnersExceptThemselves()
    {
        $this->setRoleTo('owner');

        $this->performUpdateChecking($this->users['owner'], 403);

        $this->performUpdateChecking($this->user);
    }

    public function testAdminsCanUpdateUsers()
    {
        $this->setRoleTo('admin');

        $this->update_data['role'] = 'manager';

        $this->performUpdateChecking($this->users['user']);
    }

    public function testAdminsCanUpdateManagers()
    {
        $this->setRoleTo('admin');

        $this->performUpdateChecking($this->users['manager']);
    }

    public function testAdminsCantUpdateAdminsExceptThemselves()
    {
        $this->setRoleTo('admin');

        $this->performUpdateChecking($this->users['admin'], true);

        $this->performUpdateChecking($this->user);
    }

    public function testAdminsCantAssignElevatedPermissions()
    {
        $this->setRoleTo('admin');

        $this->update_data['role'] = 'admin';

        $this->performUpdateChecking($this->users['user'], true, 422);

        $this->performUpdateChecking($this->user, true, 422);

        $this->update_data['role'] = 'owner';

        $this->performUpdateChecking($this->users['user'], true, 422);

        $this->performUpdateChecking($this->user, true, 422);
    }
        
    public function testManagersCantUpdate()
    {
        $this->setRoleTo('manager');

        $response = $this->performUpdateChecking($this->users['user'], true);

        $response = $this->performUpdateChecking($this->users['manager'], true);

        $response = $this->performUpdateChecking($this->users['admin'], true);

        $response = $this->performUpdateChecking($this->users['owner'], true);

        $response = $this->performUpdateChecking($this->user, true, 422);

        unset($this->update_data['role']);

        $response = $this->performUpdateChecking($this->user);
    }

    public function testUsersCantUpdate()
    {
        $this->setRoleTo('user');

        $response = $this->performUpdateChecking($this->users['user'], true);

        $response = $this->performUpdateChecking($this->users['manager'], true);

        $response = $this->performUpdateChecking($this->users['admin'], true);

        $response = $this->performUpdateChecking($this->users['owner'], true);

        unset($this->update_data['role']);

        $response = $this->performUpdateChecking($this->user);
    }

    public function testDuplicateUsername()
    {
        $this->setRoleTo('admin');

        $this->update_data['username'] = $this->users['owner']->username;

        $this->performUpdateChecking($this->users['user'], true, 422);
    }

    public function testDuplicateEmail()
    {
        $this->setRoleTo('admin');

        $this->update_data['email'] = $this->users['owner']->email;

        $this->performUpdateChecking($this->users['user'], true, 422);
    }

    public function testInvalidUsername()
    {
        $this->setRoleTo('owner');

        $this->update_data['username'] = ' invalid @ username ';

        $response = $this->performUpdateChecking($this->users['user'], true, 422);

        $response->assertJson([
            'errors' => [
                'username' => [
                    0 => 'The username may only contain letters, numbers, and dashes.'
                ]
            ]
        ]);
    }

    public function testInvalidEmail()
    {
        $this->setRoleTo('owner');

        $this->update_data['email'] = 'invalid-email-address';

        $response = $this->performUpdateChecking($this->users['user'], true, 422);

        $response->assertJson([
            'errors' => [
                'email' => [
                    0 => 'The email must be a valid email address.'
                ]
            ]
        ]);
    }

    public function testNoEmail()
    {
        $this->setRoleTo('owner');

        unset($this->update_data['email']);

        $response = $this->performUpdateChecking($this->users['user'], true, 422);

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

        $this->update_data['name'] = 'a';

        $response = $this->performUpdateChecking($this->users['user'], true, 422);

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

        unset($this->update_data['name']);

        $response = $this->performUpdateChecking($this->users['user'], true, 422);

        $response->assertJson([
            'errors' => [
                'name' => [
                    0 => 'The name field is required.'
                ]
            ]
        ]);
    }

    public function testInvalidRole()
    {
        $this->setRoleTo('owner');

        $this->update_data['role'] = 'a';

        $response = $this->performUpdateChecking($this->users['user'], true, 422);

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

        unset($this->update_data['role']);

        $response = $this->performUpdateChecking($this->users['user']);
    }
}
