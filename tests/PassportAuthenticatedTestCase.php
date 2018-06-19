<?php

namespace Tests;

use Inggo\Boilerplate\User;

class PassportAuthenticatedTestCase extends PassportTestCase
{
    protected $scopes = [];
    protected $user;

    protected $user_password = 'password1234';

    protected function setRoleTo($role)
    {
        $this->user->role = $role;
        $this->user->save();
    }

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'password' => bcrypt($this->user_password)
        ]);

        $token = $this->user->createToken('TestToken', $this->scopes)->accessToken;

        $this->headers['Accept'] = 'application/json';
        $this->headers['Authorization'] = 'Bearer '.$token;
    }
}
