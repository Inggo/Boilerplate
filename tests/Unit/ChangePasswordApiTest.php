<?php

namespace Tests\Unit;

use Tests\PassportAuthenticatedTestCase;
use Tests\Traits\CreatesTestUsers;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePasswordApiTest extends PassportAuthenticatedTestCase
{
    use RefreshDatabase;
    use CreatesTestUsers;

    private $post_data = [
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword'
    ];

    private function performPasswordSelfEdit($failing = false, $failing_status = 403)
    {
        $response = $this->get('/api/change-password/');

        return $this->performPasswordChange($response, $this->user, $failing, $failing_status);
    }

    private function performPasswordEdit($user, $failing = false, $failing_status = 403)
    {
        $response = $this->get('/api/change-password/' . $user->id);
        
        return $this->performPasswordChange($response, $user, $failing, $failing_status);
    }

    private function performPasswordSelfUpdate($failing = false, $failing_status = 403)
    {
        $this->post_data['current_password'] = $this->user_password;

        $response = $this->patch('/api/change-password/', $this->post_data);

        return $this->performPasswordChange($response, $this->user, $failing, $failing_status);
    }

    private function performPasswordUpdate($user, $failing = false, $failing_status = 403)
    {
        $this->post_data['id'] = $user->id;

        $response = $this->patch('/api/change-password/', $this->post_data);

        return $this->performPasswordChange($response, $user, $failing, $failing_status);
    }

    private function performPasswordChange($response, $user, $failing = false, $failing_status = 403)
    {
        if ($failing) {
            $response->assertStatus($failing_status);
            return $response;
        }

        $response->assertStatus(200);

        return $response;
    }

    public function testFailsForNonJSON()
    {
        $this->headers = [];

        $this->performPasswordSelfEdit(true, 400);

        $this->performPasswordEdit($this->users['user'], true, 400);

        $this->performPasswordSelfUpdate(true, 400);

        $this->performPasswordUpdate($this->users['user'], true, 400);
    }

    public function testFailsWhenLoggedOut()
    {
        unset($this->headers['Authorization']);

        $this->performPasswordSelfEdit(true, 401);

        $this->performPasswordEdit($this->users['user'], true, 401);

        $this->performPasswordSelfUpdate(true, 401);

        $this->performPasswordUpdate($this->users['user'], true, 401);
    }
    
    public function testSucceedsForSelf()
    {
        $this->user_password = 'incorrect-old-password';

        $this->performPasswordSelfEdit();

        $response = $this->performPasswordSelfUpdate(true, 422);

        $response->assertJsonStructure([
            'errors' => [
                'current_password'
            ]
        ]);
    }

    public function testFailsForSelfWithIncorrectPassword()
    {
        $this->performPasswordSelfEdit();

        $this->performPasswordSelfUpdate();
    }

    public function testOwnersCanChangePasswordsForNonOwners()
    {
        $this->setRoleTo('owner');

        $this->performPasswordSelfEdit();

        $this->performPasswordSelfUpdate();

        $this->performPasswordEdit($this->users['user']);

        $this->performPasswordUpdate($this->users['user']);

        $this->performPasswordEdit($this->users['manager']);

        $this->performPasswordUpdate($this->users['manager']);

        $this->performPasswordEdit($this->users['admin']);

        $this->performPasswordUpdate($this->users['admin']);

        $this->performPasswordEdit($this->users['owner'], true, 403);

        $this->performPasswordUpdate($this->users['owner'], true, 403);
    }

    public function testAdminsCanChangePasswordsForUsersAndManagers()
    {
        $this->setRoleTo('admin');

        $this->performPasswordSelfEdit();

        $this->performPasswordSelfUpdate();

        $this->performPasswordEdit($this->users['user']);

        $this->performPasswordUpdate($this->users['user']);

        $this->performPasswordEdit($this->users['manager']);

        $this->performPasswordUpdate($this->users['manager']);

        $this->performPasswordEdit($this->users['admin'], true, 403);

        $this->performPasswordUpdate($this->users['admin'], true, 403);

        $this->performPasswordEdit($this->users['owner'], true, 403);

        $this->performPasswordUpdate($this->users['owner'], true, 403);
    }

    public function testManagersCantChangeOthersPasswords()
    {
        $this->setRoleTo('manager');

        $this->performPasswordSelfEdit();

        $this->performPasswordSelfUpdate();

        $this->performPasswordEdit($this->users['user'], true, 403);

        $this->performPasswordUpdate($this->users['user'], true, 403);

        $this->performPasswordEdit($this->users['manager'], true, 403);

        $this->performPasswordUpdate($this->users['manager'], true, 403);

        $this->performPasswordEdit($this->users['admin'], true, 403);

        $this->performPasswordUpdate($this->users['admin'], true, 403);

        $this->performPasswordEdit($this->users['owner'], true, 403);

        $this->performPasswordUpdate($this->users['owner'], true, 403);
    }

    public function testUsersCantChangeOthersPasswords()
    {
        $this->setRoleTo('user');

        $this->performPasswordSelfEdit();

        $this->performPasswordSelfUpdate();

        $this->performPasswordEdit($this->users['user'], true, 403);

        $this->performPasswordUpdate($this->users['user'], true, 403);

        $this->performPasswordEdit($this->users['manager'], true, 403);

        $this->performPasswordUpdate($this->users['manager'], true, 403);

        $this->performPasswordEdit($this->users['admin'], true, 403);

        $this->performPasswordUpdate($this->users['admin'], true, 403);

        $this->performPasswordEdit($this->users['owner'], true, 403);

        $this->performPasswordUpdate($this->users['owner'], true, 403);
    }
}
