<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_variation;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function addProduct(Request $request)

    {
//        dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributeType' => 'required|string',
            'attributes' => 'nullable|array',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'image' => $imagePath,
            'attribute_type' => $request->attributeType,
        ]);




          if($request->input('attributes')){
              foreach ($request->input('attributes') as $attribute) {
                  Product_variation::create([
                      'product_id' => $product->id,
                      'attribute_quantity' => $attribute['attribute_quantity'],
                      'attribute_price' => $attribute['attribute_price'],

                  ]);
              }
          }


        return response()->json(['success' => true, 'product' => $product], 201);
    }
}
