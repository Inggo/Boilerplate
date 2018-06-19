<?php

namespace Tests\Traits;

use Inggo\Boilerplate\User;

trait CreatesTestUsers
{
    protected $users = [];

    private $roles = [
        'user', 'manager', 'admin', 'owner',
    ];

    public function setUp()
    {
        parent::setUp();

        foreach ($this->roles as $role) {
            $this->users[$role] = factory(User::class)->create(['role' => $role]);
        }
    }
}
