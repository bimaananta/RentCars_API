<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Pages::latest()->get();

        if(is_null($pages->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No pages found"
            ], 403);
        }

        $response = [
            "status" => "success",
            "messgae" => "Get pages success",
            "data" => $pages
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
            'title' => "required", 'description' => "required", 'image' => "required|image|mimes:png,jpg,jpeg,gif|max:2048", 'id_category' => "required", 'id_user' => "required", 'status' => "required"
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        if($file = $request->file('image')){
            $path = "images/";
            $fileName = time().$file->getClientOriginalName();
            $filePath = $path.$fileName;
            Storage::disk('public')->put($filePath, File::get($file));
        }

        $input = $request->all();
        $input["links"] = Str::slug($request->title);
        $input["image"] = $filePath;

        $pages = Pages::create($input);

        return response()->json([
            "status" => "success",
            "message" => "Pages is added successfully",
            "data" => $pages
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pages = Pages::find($id);
  
        if (is_null($pages)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Pages is not found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'Pages is retrieved successfully.',
            'data' => $pages,
        ];
        
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pages $pages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'title' => "required", 'description' => "required", 'image' => "required", 'id_category' => "required", 'id_user' => "required", 'status' => "required"
        ]);

        if($validation->fails()){  
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        $pages = Pages::find($id);

        if (is_null($pages)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Pages is not found!',
            ], 200);
        }

        $pages->update($request->all());
        
        $response = [
            'status' => 'success',
            'message' => 'Pages is updated successfully.',
            'data' => $pages,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pages = Pages::find($id);

        if(is_null($pages)){
            return response()->json([
                "status" => "failed",
                "message" => "pages not found"
            ], 403);
        }

        Pages::destroy($id);
        return response()->json([
            "status" => "success",
            "message" => "delete pages successfully",
            "data" => $pages
        ], 200);
    }

    public function search($name)
    {
        $pages = Pages::where('title', 'like', '%'. $name . '%')->latest()->get();

        if(is_null($pages->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No pages found!"
            ], 403);
        }

        $response = [
            "status" => "success",
            "message" => "Get pages data successfully",
            "data" => $pages
        ];

        return response()->json($response, 200);
    }
}
