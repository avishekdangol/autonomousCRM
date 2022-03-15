<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as ResourcesUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
     // GET USERS EXCEPT ADMIN
     public function users() {
        $users = User::where('admin', 0)->get();
        return ResourcesUser::collection($users);
    }

    // GET ALL USERS
    public function all_users() {
        $users = User::get();
        return ResourcesUser::collection($users);
    }

    // CHANGE PASSWORD
    public function update(Request $request) {
        $data = $request->all();
        $user = User::findOrFail($data['id']);

        if(Hash::check($data['current'], $user->password)) {
            $user->password = Hash::make($data['password']);
            $user->save();
            return 'success';
        } else return 'failed';
    }

    // REMOVE STAFFS
    public function destroy($id) {
        try {
            User::findOrFail($id)->delete();
            return 'deleted';
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
