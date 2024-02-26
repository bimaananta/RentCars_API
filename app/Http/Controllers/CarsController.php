<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Cars::latest()->get();

        if(is_null($cars->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No cars found"
            ], 403);
        }

        $response = [
            "status" => "success",
            "messgae" => "Get cars success",
            "data" => $cars
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
            'title' => "required", 'description' => "required", 'photo1' => "required|image|mimes:png,jpg,jpeg,gif,svg|max:2048", 'photo2' => "required|image|mimes:png,jpg,jpeg,gif,svg|max:2048", 'brand' => "required", 'model' => "required", 'fuel_type' => "required", 'price' => "required", 'gearbox' => "required", 'available' => "required", 'status' => "required"
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        $path = "images/";

        if($file = $request->file('photo1')){
            $fileName = time().$file->getClientOriginalName();
            $filePath = $path.$fileName;
            Storage::disk('public')->put($filePath, File::get($file));
        }

        if($file2 = $request->file('photo2')){
            $fileName2 = time().$file2->getClientOriginalName();
            $filePath2 = $path.$fileName2;
            Storage::disk('public')->put($filePath2, File::get($file2));
        }

        $input = $request->except(['photo1', 'photo2', 'link']);
        $cars = Cars::create($input);

        $link = Str::slug($input["title"]);
        $cars->update(['photo1' => $filePath, 'photo2' => $filePath2, 'link' => $link]);

        return response()->json([
            "status" => "success",
            "message" => "Cars is added successfully",
            "data" => Cars::find($cars->id)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cars = Cars::find($id);
  
        if (is_null($cars)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product is not found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'Cars is retrieved successfully.',
            'data' => $cars,
        ];
        
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cars $cars)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'title' => "required", 'description' => "required", 'photo1' => "image|mimes:png,jpg,jpeg,gif,svg|max:2048", 'photo2' => "image|mimes:png,jpg,jpeg,gif,svg|max:2048", 'brand' => "required", 'model' => "required", 'fuel_type' => "required", 'price' => "required", 'gearbox' => "required", 'available' => "required", 'status' => "required"
        ]);

        if($validation->fails()){  
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        
        $cars = Cars::find($id);

        if (is_null($cars)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Cars is not found!',
            ], 200);
        }

        $input = $request->except(['photo1', 'photo2']);

        if($request->hasFile('photo1')){
            $latestFile = $cars->photo1;
            Storage::disk('public')->delete($latestFile);

            $file = $request->file('photo1');
            $path = "images/";
            $fileName = time().$file->getClientOriginalName();
            $filePath = $path.$fileName;
            $input["photo1"] = $filePath;

            Storage::disk('public')->put($filePath, File::get($file));
        }
        
        if($request->hasFile('photo2')){
            $latestFile2 = $cars->photo2;
            Storage::disk('public')->delete($latestFile2);

            $file2 = $request->file('photo2');
            $path2 = "images/";
            $fileName2 = time().$file2->getClientOriginalName();
            $filePath2 = $path2.$fileName2;
            $input["photo2"] = $filePath2;

            Storage::disk('public')->put($filePath2, File::get($file2));
        }
        
        $input["link"] = Str::slug($request->title);
        $cars->update($input);
        
        $response = [
            'status' => 'success',
            'message' => 'Car is updated successfully.',
            'data' => $cars,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cars = Cars::find($id);

        if(is_null($cars)){
            return response()->json([
                "status" => "failed",
                "message" => "cars not found"
            ], 403);
        }

        Cars::destroy($id);
        return response()->json([
            "status" => "success",
            "message" => "delete cars successfully",
            "data" => $cars
        ], 200);
    }

    public function search($name)
    {
        $cars = Cars::where('title', 'like', '%'. $name . '%')->latest()->get();

        if(is_null($cars->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No cars found!"
            ], 403);
        }

        $response = [
            "status" => "success",
            "message" => "Get cars data successfully",
            "data" => $cars
        ];

        return response()->json($response, 200);
    }
}

