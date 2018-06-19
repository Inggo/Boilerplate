<?php

namespace Tests\Unit;

use Tests\PassportAuthenticatedTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Inggo\Boilerplate\User;

class UsersApiCheckEmailTest extends PassportAuthenticatedTestCase
{
    use RefreshDatabase;

    private $test_user_data = [
        'username' => 'testuser',
        'email' => 'testuser@example.com',
        'name' => 'Test User',
        'password' => 'testing',
        'password_confirmation' => 'testing',
        'role' => 'user',
    ];

    private function postCheckEmail()
    {
        return $this->post('/api/check-email', [
            'email' => $this->test_user_data['email']
        ]);
    }

    private function performEmailChecking($failing = false, $failing_status = 403)
    {
        $response = $this->postCheckEmail();

        if ($failing) {
            $response->assertStatus($failing_status);
            return;
        }

        $response->assertStatus(200)
            ->assertJson([
                'available' => true
            ]);
        
        $this->post('/api/users/', $this->test_user_data);

        $response = $this->postCheckEmail();

        $response->assertStatus(200)
            ->assertJson([
                'available' => false
            ]);
    }

    public function testOwnerEmailChecking()
    {
        $this->setRoleTo('owner');

        $this->performEmailChecking();
    }

    public function testAdminEmailChecking()
    {
        $this->setRoleTo('admin');

        $this->performEmailChecking();
    }

    public function testManagerEmailChecking()
    {
        $this->setRoleTo('manager');

        $this->performEmailChecking(true);
    }

    public function testUserEmailChecking()
    {
        $this->setRoleTo('manager');

        $this->performEmailChecking(true);
    }

    public function testCheckEmailFailsForNonJSON()
    {
        $this->headers = [];

        $this->performEmailChecking(true, 400);
    }

    public function testCheckEmailFailsWhenLoggedOut()
    {
        unset($this->headers['Authorization']);

        $this->performEmailChecking(true, 401);
    }
}
