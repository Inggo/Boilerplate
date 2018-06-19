<?php

namespace Inggo\Boilerplate\Http\Controllers;

use Illuminate\Http\Request;
use Inggo\Boilerplate\User;
use Inggo\Boilerplate\Http\Requests\ChangePassword;
use Illuminate\Http\Resources\Json\Resource;

use Auth;

class ChangePasswordController extends Controller
{
    private function checkUser(Request $request)
    {
        $user = $request->id ? User::find($request->id) : Auth::user();

        $this->authorize('update', $user);

        return $user;
    }

    public function edit(Request $request)
    {
        $user = $this->checkUser($request);

        return response()->json([
            'allowed' => true,
            'user' => new Resource($user)
        ]);
    }

    public function update(ChangePassword $request)
    {
        $user = $this->checkUser($request);

        $user->password = bcrypt($request->password);
        $user->save();

        return new Resource($user);
    }
}
