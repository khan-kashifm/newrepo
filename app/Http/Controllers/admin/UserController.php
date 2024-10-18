<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(3);

        return view('admin.user.list', [
            'users' => $users
        ]);
    }

    public function deleteUser(Request $request)
    {
        User::where('id', $request->id)->delete();
    }


    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view("admin.user.edit", [
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id . ',id',
        ]);

        if ($validator->passes()) {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();

           
            return redirect()->route('admin.users')->with('success', 'User Information Updated Successfully.');
        } else {
          
            return redirect()->route('admin.users')->with('error', 'User Information Not Updated');
        }
    }
}
