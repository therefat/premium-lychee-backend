<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function list()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }
    public function loginPost(Request $request)
    {
//        $val = \Illuminate\Support\Facades\Validator::make($request->all(), [
//            'email' => 'required|email',
//            'password' => 'required',
//        ]);
//        if ($val->fails()) {
//            return response()->json(['errors' => $val->errors()],400);
//        }
//        $credentials = $request->only('email', 'password');
//        if(auth()->attempt($credentials)){
//            return  response()->json(['message' => 'Login successful', 'user' => auth()->user()], 200);
//        }
//        return response()->json(['error' => 'Invalid email or password'], 401);
        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            // Get the authenticated admin
            $admin = Auth::guard('admin')->user();

            // Create a token for the admin
            $token = $admin->createToken('AdminToken')->plainTextToken;
            $role = Role::findOrFail($admin->role_id);

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'admin' => $admin,
                'role' => $role->name
            ], 200);}
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

  public  function register(Request $request)
  {
        $validate = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',


        ]);
        if($validate->fails()){
            return response()->json(['errrors' => $validate->errors(), ],400);
        }
        $hashedPassword = bcrypt($request->password);
        $admin = Admin::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $hashedPassword,
            'role' => 'admin',
        ]);
        return response()->json(['message' => 'Admin created successfully', 'data' => $admin], 201);

    }
}
