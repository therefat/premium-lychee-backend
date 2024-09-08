<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Dotenv\Validator;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //
public function store(Request $request){
    $validated = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'name' => 'required',
        'description' => 'required',
    ]);
    if($validated->fails()){
        return response()->json($validated->errors()->toJson(), 400);
    }
    $input = $request->all();
    $categories = Categories::create($input);

    return response()->json([
        "message"=>"Category created",
        "category"=>$categories
    ]);
}

}
