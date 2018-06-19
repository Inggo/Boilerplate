<?php

namespace Tests\Unit;

use Tests\PassportAuthenticatedTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Inggo\Boilerplate\User;

class UsersApiIndexTest extends PassportAuthenticatedTestCase
{
    use RefreshDatabase;

    private function checkIndexFields($response)
    {
        $response->assertJsonStructure([
            'data' => [
                0 => [
                    'id',
                    'email',
                    'name',
                ]
            ]
        ])->assertJsonMissing([
            'data' => [
                0 => [
                    'password'
                ]
            ]
        ]);
    }

    private function performIndexTest($failing = false, $failing_status = 403)
    {
        factory(User::class, 5)->create();

        $response = $this->get('/api/users/');

        if ($failing) {
            $response->assertStatus($failing_status);
            return;
        }

        $response->assertStatus(200);

        $this->assertCount(6, $response->getData()->data);

        $this->checkIndexFields($response);

        factory(User::class, 5)->create();

        $response = $this->get('/api/users/');

        $response->assertStatus(200);

        $this->assertCount(11, $response->getData()->data);

        $this->checkIndexFields($response);
    }

    public function testIndexFailsForNonJSON()
    {
        $this->headers = [];

        $this->performIndexTest(true, 400);
    }

    public function testIndexFailsWhenLoggedOut()
    {
        unset($this->headers['Authorization']);

        $this->performIndexTest(true, 401);
    }

    public function testIndexForUsers()
    {
        $this->setRoleTo('user');

        $this->performIndexTest(true);
    }

    public function testIndexForManagers()
    {
        $this->setRoleTo('manager');

        $this->performIndexTest(true);
    }

    public function testIndexForOwners()
    {
        $this->setRoleTo('owner');

        $this->performIndexTest();
    }

    public function testIndexForAdmins()
    {
        $this->setRoleTo('admin');

        $this->performIndexTest();
    }
}
