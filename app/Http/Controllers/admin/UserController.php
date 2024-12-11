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

    public function show()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(3);

        return $users;
    }

    public function validateToken(Request $request)
    {

        $token = $request->bearerToken();
        $user = User::where('bearer_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ],);
        }
        return response()->json(['success' => true, 'message' => 'Authorized']);
    }

    public function edit($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }



    public function deleteUser(Request $request)
    {

        $token = $request->bearerToken();
        $user = User::where('bearer_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token'
            ],);
        }

        $newuser = User::where([
            ['id', $request->id]
        ])->first();
        // dd($newuser);


        if ($newuser === null) {

            session()->flash('error', 'Either User deleted or not found');
            return response()->json([
                'status' => false,
                'message' => 'User not found or already deleted'
            ]);
        }

        $newuser =  User::where('id', $request->id)->delete();

        session()->flash('success', 'User deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'User deleted'
        ]);
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

        $token = $request->bearerToken();
        $user = User::where('bearer_token', $token)->first();



        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token'
            ],);
        }


        $updateuser = User::find($id);

        // if (!$updateuser) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'No user found with this ID',
        //     ]);
        // }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
        // $updateuser = User::find($id);

        $updateuser->name = $request->name;
        $updateuser->email = $request->email;
        $updateuser->designation = $request->designation;
        $updateuser->mobile = $request->mobile;
        $updateuser->save();

        return response()->json([
            'status' => true,
            'message' => 'User details updated successfully'
        ]);
    }


    //     return redirect()->route('admin.users')->with('success', 'User Information Updated Successfully.');
    // } else {

    //     return redirect()->route('admin.users')->with('error', 'User Information Not Updated');
    // }

}
