<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::latest()->get();

        if(is_null($category->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No category found"
            ], 403);
        }

        $response = [
            "status" => "success",
            "message" => "Get category success",
            "data" => $category
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
            'name' => "required", 'description' => "required", 'status' => "required"
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        $category = Category::create($request->all());

        return response()->json([
            "status" => "success",
            "message" => "Category is added successfully",
            "data" => $category
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::where('id_category', $id)->firstOrFail();
  
        if (is_null($category)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Category is not found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'Category is retrieved successfully.',
            'data' => $category,
        ];
        
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => "required", 'description' => "required",'status' => "required"
        ]);

        if($validation->fails()){  
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        $category = Category::where('id_category', $id)->firstOrFail();

        if (is_null($category)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Category is not found!',
            ], 200);
        }

        $category->update($request->all());
        
        $response = [
            'status' => 'success',
            'message' => 'Category is updated successfully.',
            'data' => $category,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::where('id_category', $id)->firstOrFail();

        if(is_null($category)){
            return response()->json([
                "status" => "failed",
                "message" => "category not found"
            ], 403);
        }

        $category->delete();
        return response()->json([
            "status" => "success",
            "message" => "delete category successfully",
            "data" => $category
        ], 200);
    }

    public function search($name)
    {
        $category = Category::where('name', 'like', '%'. $name . '%')->latest()->get();

        if(is_null($category->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No category found!"
            ], 403);
        }

        $response = [
            "status" => "success",
            "message" => "Get category data successfully",
            "data" => $category
        ];

        return response()->json($response, 200);
    }
}
