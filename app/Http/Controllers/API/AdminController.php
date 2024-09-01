<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
//       
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name']= $user->
        }
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
