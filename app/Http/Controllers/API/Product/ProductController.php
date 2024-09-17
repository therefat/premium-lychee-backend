<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_variation;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //
    public function addProduct(Request $request)

    {
//        dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required',
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
            'category_id' => $request->category_id,

            'price' => $request->price,
            'image' => asset('storage/' . $imagePath),
            'attribute_type' => $request->attributeType,
        ]);




          if($request->input('attributes')){
              foreach ($request->input('attributes') as $attribute) {
                  ProductVariation::create([
                      'product_id' => $product->id,
                      'attribute_quantity' => $attribute['attribute_quantity'],
                      'attribute_price' => $attribute['attribute_price'],

                  ]);
              }
          }


        return response()->json(['success' => true, 'product' => $product], 201);
    }
    public function show($id)
    {
        // Find the product by ID
        $product = Product::with('attributes')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            "success"=> true,
             "product" => $product
        ], 200);
    }
    public function getProduct(){
        $products = Product::with('categories')->get();
        if(!$products){
            return response()->json(['message' => 'Products not found'],);
        }
        return response()->json([
            "products" => $products
        ]);
}
    public function updateProduct(Request $request,$id)


    {
//        dd($request->all());
        // Validate the incoming request
        $product = Product::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributeType' => 'required|string',
            'attributes' => 'nullable|array',
        ]);

        // Handle image upload if a new image is provided
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Optionally, delete the old image if it exists
            if ($imagePath) {
                Storage::disk('public')->delete(str_replace(asset('storage/'), '', $imagePath));
            }

            $imagePath = $request->file('image')->store('product_images', 'public');
            $imagePath = asset('storage/' . $imagePath);
        }

        // Update the product with new data
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'image' => $imagePath,
            'attribute_type' => $request->attributeType,
        ]);

       
        if ($request->input('attributes')) {

            ProductVariation::where('product_id', $product->id)->delete();

            // Add new attributes
            foreach ($request->input('attributes') as $attribute) {
                ProductVariation::create([
                    'product_id' => $product->id,
                    'attribute_quantity' => $attribute['attribute_quantity'],
                    'attribute_price' => $attribute['attribute_price'],
                ]);
            }
        }

        return response()->json(['success' => true, 'product' => $product], 200);
    }

}
