<?php

namespace Inggo\Boilerplate\Http\Controllers\Admin;

use Inggo\Boilerplate\User;
use Illuminate\Http\Request;
use Inggo\Boilerplate\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\Resource;
use Inggo\Boilerplate\Http\Requests\StoreUser;
use Inggo\Boilerplate\Http\Requests\UpdateUser;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', User::class);

        return new ResourceCollection(User::all());
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return response()->json([
            'allowed' => true,
            'allowed_roles_to_assign' => Auth::user()->allowed_roles_to_assign,
            'username_required' => env('ADMIN_REQUIRE_USERNAME', true),
            'email_required' => env('ADMIN_REQUIRE_EMAIL', true),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $this->authorize('create', User::class);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return new Resource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['actions', 'actions.subject', 'actions.causer'])->find($id);

        $this->authorize('view', $user);

        return new Resource($user);
    }

    public function edit(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        $this->authorize('update', $user);

        return response()->json([
            'allowed' => true,
            'allowed_roles_to_assign' => Auth::user()->allowed_roles_to_assign,
            'username_required' => env('ADMIN_REQUIRE_USERNAME', true),
            'email_required' => env('ADMIN_REQUIRE_EMAIL', true),
            'user' => new Resource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, $id)
    {
        $user = User::find($id);

        $this->authorize('update', $user);

        $user->fill([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        if ($request->role) {
            $user->role = $request->role;
        }
        
        $user->save();

        return new Resource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'success' => true,
            'user' => new Resource($user)
        ]);
    }

    public function checkEmail(Request $request)
    {
        $this->authorize('create', User::class);

        if ($request->user && User::find($request->user)->email === $request->email) {
            return response()->json(['available' => true]);
        }

        if (Auth::user()->email === $request->email && $request->editing) {
            return response()->json(['available' => true]);
        }

        return response()->json([
            'available' => User::where('email', $request->email)->count() == 0
        ]);
    }

    public function checkUsername(Request $request)
    {
        $this->authorize('create', User::class);

        if ($request->user && User::find($request->user)->username === $request->username) {
            return response()->json(['available' => true]);
        }

        if (Auth::user()->username === $request->username && $request->editing) {
            return response()->json(['available' => true]);
        }

        return response()->json([
            'available' => User::where('username', $request->username)->count() == 0
        ]);
    }
}
