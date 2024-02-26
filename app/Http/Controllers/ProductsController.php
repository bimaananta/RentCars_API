<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::latest()->get();

        if(is_null($products->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No products found"
            ], 403);
        }

        $response = [
            "status" => "success",
            "messgae" => "Get products success",
            "data" => $products
        ];

        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => "required", 'image' => "required", 'description' => "required"
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        $products = Products::create($request->all());

        return response()->json([
            "status" => "success",
            "message" => "Products is added successfully",
            "data" => $products
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $products = Products::find($id);
  
        if (is_null($products)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product is not found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'Products is retrieved successfully.',
            'data' => $products,
        ];
        
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => "required", 'image' => "required", 'description' => "required"
        ]);

        if($validation->fails()){  
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        $products = Products::find($id);

        if (is_null($products)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Products is not found!',
            ], 200);
        }

        $products->update($request->all());
        
        $response = [
            'status' => 'success',
            'message' => 'Product is updated successfully.',
            'data' => $products,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $products = Products::find($id);

        if(is_null($products)){
            return response()->json([
                "status" => "failed",
                "message" => "products not found"
            ], 403);
        }

        Products::destroy($id);
        return response()->json([
            "status" => "success",
            "message" => "delete products successfully",
            "data" => $products
        ], 200);
    }

    public function search($name)
    {
        $products = Products::where('name', 'like', '%'. $name . '%')->latest()->get();

        if(is_null($products->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No products found!"
            ], 403);
        }

        $response = [
            "status" => "success",
            "message" => "Get products data successfully",
            "data" => $products
        ];

        return response()->json($response, 200);
    }
}
