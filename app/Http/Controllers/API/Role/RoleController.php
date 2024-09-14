<?php

namespace App\Http\Controllers\Api\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //

    public function getAllRoles(){

        $roles = Role::all();
        if(!$roles){
            return response()->json(["message" => "No roles found"], 404);
        }
        return response()->json([
            'roles' => $roles
        ]);
    }
public function createRole(Request $request)

{
//    dd($request->description);
    $request->validate([
        'name' => 'required|string',
        'description' => 'required',
        'status' => 'required|string'
    ]);
    $role = Role::create([
        'name' => $request->name,
        'description' =>  $request->description,
        'status' => $request->status
    ]);
    return response()->json([
        'message' => 'Role created successfully',
        'role' => $role
    ]);
}
public function destroy($id){
        $role = Role::find($id);
        if(!$role){
//            dd('test');
            return response()->json(["message" => "Role not found"], 404);
        }
        $role->delete();
        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
}
}
