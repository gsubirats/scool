<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\AddUser;
use App\Http\Requests\DeleteUser;
use App\Http\Requests\ShowUsersManagement;
use App\Http\Resources\Tenant\UserCollection;
use App\Http\Resources\Tenant\UserResource;
use App\Http\Resources\Tenant\UserTypeCollection;
use App\Http\Resources\Tenant\UserTypesCollection;
use App\Models\User;
use App\Models\UserType;
use Spatie\Permission\Models\Role;

/**
 * Class UsersController.
 *
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    /**
     * @param ShowUsersManagement $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowUsersManagement $request)
    {
        $users = (new UserCollection(User::with('roles')->get()))->transform();
        $userTypes = (new UserTypesCollection(UserType::with('roles')->get()))->transform();
        $roles = Role::all()->pluck('name');
        return view('tenants.users.show',compact('users','userTypes','roles'));
    }

    /**
     * Show users.
     *
     * @param ShowUsersManagement $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(ShowUsersManagement $request)
    {
        return User::all();
    }

    /**
     * Store user on database.
     *
     * @param AddUser $request
     * @return mixed
     */
    public function store(AddUser $request)
    {
        $user = $this->storeUser($request);

        if ($request->roles) {
            foreach ( $request->roles as $role) {
                $user->assignRole(Role::findByName($role,'web'));
            }
        }

        return new UserResource($user);
    }

    /**
     * Store user.
     *
     * @param $request
     * @return mixed
     */
    protected function storeUser($request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            // TODO
            'password' => bcrypt('secret')
        ]);
    }

    /**
     * Store user on database and invite.
     *
     * @param AddUser $request
     * @return mixed
     */
    public function store_and_invite(AddUser $request)
    {
        // TODO send invitation
        return $this->storeUser($request);
    }

    /**
     * Delete user.
     *
     * @param DeleteUser $request
     * @param User $user
     * @return User
     */
    public function destroy(DeleteUser $request, $tenant, User $user)
    {
        $user->delete();
        return $user;
    }
}
