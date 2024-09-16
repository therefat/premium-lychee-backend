<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Dotenv\Validator;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //

    public  function getCategories()
    {
    $categories = Categories::all();
    if(!$categories){
        return respnse()->json([
            "message" => "Category Not Found"
        ]);
    }
    return response()->json($categories);
    }
public function store(Request $request){
    $validated = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'name' => 'required',
        'description' => 'required',
        'status' => 'required',
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
public  function destroy($id)
{
    $categorie = Categories::find($id);
    if(!$categorie){
        return response()->json([
            "message"=>"Category not found"
        ],404);
    }
    $categorie->delete();
    return response()->json([
        "message"=>"Category deleted"
    ]);
}

}
