<?php

namespace Tests\Unit;

use Tests\PassportAuthenticatedTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Inggo\Boilerplate\User;

class UsersApiCreateTest extends PassportAuthenticatedTestCase
{
    use RefreshDatabase;

    private function performCreateChecking($failing = false, $failing_status = 403)
    {
        $response = $this->get('/api/users/create/');

        if ($failing) {
            $response->assertStatus($failing_status);
            return $response;
        }

        $response->assertStatus(200)
            ->assertJson([
                'allowed' => true,
                'allowed_roles_to_assign' => $this->user->role == 'owner' ?
                    [
                        0 => 'owner',
                        1 => 'admin',
                        2 => 'manager',
                        3 => 'user'
                    ] :
                    [
                        0 => 'manager',
                        1 => 'user'
                    ]
            ]);

        return $response;
    }

    public function testCreateFailsForNonJSON()
    {
        $this->headers = [];

        $this->performCreateChecking(true, 400);
    }

    public function testCreateFailsWhenLoggedOut()
    {
        unset($this->headers['Authorization']);

        $this->performCreateChecking(true, 401);
    }

    public function testOwnersCanCreate()
    {
        $this->setRoleTo('owner');

        $this->performCreateChecking();
    }

    public function testAdminsCanCreate()
    {
        $this->setRoleTo('admin');

        $this->performCreateChecking();
    }

    public function testManagersCantCreate()
    {
        $this->setRoleTo('manager');

        $this->performCreateChecking(true);
    }

    public function testUsersCantCreate()
    {
        $this->setRoleTo('user');

        $this->performCreateChecking(true);
    }
}
