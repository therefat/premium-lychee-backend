<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        };
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
//        return $this->sendResponse($success, 'User register successfully.');
//        return $this->s($success, 'User register successfully.');
//        return response()->json(["message": "success",], 200);
//        return response()->json("message":"successfuly",$success, 200);
        return response()->json([
            'message' => 'successfully',
            'data' => $success
        ], 200);

}
public function login(Request $request): JsonResponse{
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
        $user = Auth::user();
        $token= $user->createToken('MyApp')->plainTextToken;

        return response()->json([
        'message' => 'successfully loggedin',
            'token' => $token,
            'name' => $user->name,
            'id' => $user->id,
            'email' => $user->email
        ]);

        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);
        }
}
}
