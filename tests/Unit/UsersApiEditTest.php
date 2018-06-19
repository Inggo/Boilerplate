<?php

namespace Tests\Unit;

use Tests\PassportAuthenticatedTestCase;
use Tests\Traits\CreatesTestUsers;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inggo\Boilerplate\User;

class UsersApiEditTest extends PassportAuthenticatedTestCase
{
    use RefreshDatabase;
    use CreatesTestUsers;

    private function performEditChecking($user, $failing = false, $failing_status = 403)
    {
        $response = $this->get('/api/users/' . $user->id . '/edit/');

        if ($failing) {
            $response->assertStatus($failing_status);
            return $response;
        }

        $response->assertStatus(200)
            ->assertJson([
                'allowed' => true,
                'allowed_roles_to_assign' => $this->user->allowed_roles_to_assign
            ]);

        return $response;
    }

    public function testEditFailsForNonJSON()
    {
        $this->headers = [];

        $this->performEditChecking($this->users['user'], true, 400);
    }

    public function testEditFailsWhenLoggedOut()
    {
        unset($this->headers['Authorization']);

        $this->performEditChecking($this->users['user'], true, 401);
    }

    public function testOwnerEdits()
    {
        $this->setRoleTo('owner');

        $this->performEditChecking($this->users['user']);

        $this->performEditChecking($this->users['manager']);

        $this->performEditChecking($this->users['admin']);

        $this->performEditChecking($this->users['owner'], true);

        $this->performEditChecking($this->user);
    }

    public function testAdminEdits()
    {
        $this->setRoleTo('admin');

        $this->performEditChecking($this->users['user']);

        $this->performEditChecking($this->users['owner'], true);

        $this->performEditChecking($this->users['admin'], true);

        $this->performEditChecking($this->users['manager']);

        $this->performEditChecking($this->user);
    }

    public function testManagerEdits()
    {
        $this->setRoleTo('manager');

        $this->performEditChecking($this->users['user'], true);

        $this->performEditChecking($this->users['manager'], true);

        $this->performEditChecking($this->users['admin'], true);

        $this->performEditChecking($this->users['owner'], true);

        $this->performEditChecking($this->user);
    }

    public function testUserEdits()
    {
        $this->setRoleTo('user');

        $this->performEditChecking($this->users['user'], true);

        $this->performEditChecking($this->users['manager'], true);

        $this->performEditChecking($this->users['admin'], true);

        $this->performEditChecking($this->users['owner'], true);

        $this->performEditChecking($this->user);
    }
}
