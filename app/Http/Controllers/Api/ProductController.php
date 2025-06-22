<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();
        if ($products->count() > 0) {
            return ProductResource::collection($products);
        } else {
            return response()->json(['message', 'No recores Available'], 200);
        }
    }

    // POST /api/products
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['message', $validator->errors()], 422);
        }

        $product = Product::create($request->all());
        return response()->json([
            'message' => 'Product Created Successfully',
            'data' => new ProductResource($product)
        ], 200);
    }

    // GET /api/products/{id}
    // public function show(Product $product)
    // {
    //     return new ProductResource($product);
    // }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return new ProductResource($product);
    }

    // PUT/PATCH /api/products/{id}
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Product Updated Successfully',
            'data' => new ProductResource($product)
        ], 200);
    }

    // DELETE /api/products/{id}
    public function destroy($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'message' => 'Product not found'
        ], 404);
    }

    $product->delete();

    return response()->json([
        'message' => 'Product deleted successfully'
    ], 200);
}
}
