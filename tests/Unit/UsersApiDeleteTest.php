<?php

namespace Tests\Unit;

use Tests\PassportAuthenticatedTestCase;
use Tests\Traits\CreatesTestUsers;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inggo\Boilerplate\User;

class UsersApiDeleteTest extends PassportAuthenticatedTestCase
{
    use RefreshDatabase;
    use CreatesTestUsers;

    private function performDelete($user, $expected_count, $failing = false, $failing_status = 403)
    {
        $response = $this->delete('/api/users/' . $user->id);

        $this->assertCount($expected_count, User::all());

        if ($failing) {
            $response->assertStatus($failing_status);
            return $response;
        }

        $response->assertStatus(200);

        return $response;
    }

    public function testDeleteFailsForNonJSON()
    {
        $this->headers = [];

        $this->performDelete($this->users['user'], User::all()->count(), true, 400);
    }

    public function testDeleteFailsWhenLoggedOut()
    {
        unset($this->headers['Authorization']);

        $this->performDelete($this->users['user'], User::all()->count(), true, 401);
    }

    public function testOwnerDeletes()
    {
        $this->setRoleTo('owner');

        $count = User::all()->count();

        $this->performDelete($this->users['user'], $count - 1);

        $this->performDelete($this->users['manager'], $count - 2);

        $this->performDelete($this->users['admin'], $count - 3);

        $this->performDelete($this->users['owner'], $count - 3, true);

        $this->performDelete($this->user, $count - 3, true);
    }

    public function testAdminDeletes()
    {
        $this->setRoleTo('admin');

        $count = User::all()->count();

        $this->performDelete($this->users['user'], $count - 1);

        $this->performDelete($this->users['manager'], $count - 2);

        $this->performDelete($this->users['owner'], $count - 2, true);

        $this->performDelete($this->users['admin'], $count - 2, true);

        $this->performDelete($this->user, $count - 2, true);
    }

    public function testManagerDeletes()
    {
        $this->setRoleTo('manager');

        $count = User::all()->count();

        $this->performDelete($this->users['user'], $count, true);

        $this->performDelete($this->users['manager'], $count, true);

        $this->performDelete($this->users['admin'], $count, true);

        $this->performDelete($this->users['owner'], $count, true);

        $this->performDelete($this->user, $count, true);
    }

    public function testUserDeletes()
    {
        $this->setRoleTo('user');

        $count = User::all()->count();

        $this->performDelete($this->users['user'], $count, true);

        $this->performDelete($this->users['manager'], $count, true);

        $this->performDelete($this->users['admin'], $count, true);

        $this->performDelete($this->users['owner'], $count, true);

        $this->performDelete($this->user, $count, true);
    }
}
