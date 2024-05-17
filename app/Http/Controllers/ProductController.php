<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Laravel API",
 *     description="API Documentation for the Laravel API",
 *     @OA\Contact(
 *         email="your-email@example.com"
 *     ),
 * )
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get list of products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No products found"
     *     ),
     * )
     */
    public function index()
    {
        // Retrieve products from cache or fetch from database and cache them for 1 day
        $products = Cache::remember('products', 60 * 60 * 24, function () {
            return Product::all();
        });

        // Check if the product list is empty
        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], 200);
        }

        // Return the list of products
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     *
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     * )
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'product_price' => 'required|string',
            'product_discount_price' => 'nullable|numeric',
            'product_quantity' => 'required|integer',
            'product_images' => 'required|string',
            'product_manufacturer' => 'required|string|max:255',
            'product_status' => 'required|in:A,D',
            'product_slug' => 'required|string|max:255|unique:products,product_slug',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new product with the validated data
        $product = Product::create($request->all());

        // Clear the cached products
        Cache::forget('products');

        // Return the newly created product
        return response()->json($product, 201);
    }

    /**
     * Display the specified product by ID.
     *
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     * )
     */
    public function show($id)
    {
        // Find the product by ID
        $product = Product::find($id);

        // If product not found, return error response
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Return the found product
        return response()->json($product);
    }

    /**
     * Update the specified product by ID.
     *
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update a product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        // Find the product by ID
        $product = Product::find($id);

        // If product not found, return error response
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'product_name' => 'sometimes|required|string|max:255',
            'product_description' => 'sometimes|required|string',
            'product_price' => 'sometimes|required|string',
            'product_discount_price' => 'nullable|numeric',
            'product_quantity' => 'sometimes|required|integer',
            'product_images' => 'sometimes|required|string',
            'product_manufacturer' => 'sometimes|required|string|max:255',
            'product_status' => 'sometimes|required|in:A,D',
            'product_slug' => 'sometimes|required|string|max:255|unique:products,product_slug,' . $product->id,
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update the product with the validated data
        $product->update($request->all());

        // Clear the cached products
        Cache::forget('products');

        // Return the updated product
        return response()->json($product);
    }

    /**
     * Remove the specified product by ID.
     *
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     * )
     */
    public function destroy($id)
    {
        // Find the product by ID
        $product = Product::find($id);

        // If product not found, return error response
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Delete the product
        $product->delete();

        // Clear the cached products
        Cache::forget('products');

        // Return no content response
        return response()->json(null, 204);
    }
}
