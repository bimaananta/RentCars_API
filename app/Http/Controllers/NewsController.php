<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::latest()->get();

        if(is_null($news->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No news found"
            ], 403);
        }

        $response = [
            "status" => "success",
            "messgae" => "Get news success",
            "data" => $news
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

        $input = $request->all();
        $input["links"] = Str::slug($request->title);

        if($file = $request->file('image')){
            $path = "images/";
            $fileName = time().$file->getClientOriginalName();
            $filePath = $path.$fileName;
            Storage::disk('public')->put($filePath, File::get($file));
            $input["image"] = $filePath;
        }

        $news = News::create($input);

        return response()->json([
            "status" => "success",
            "message" => "News is added successfully",
            "data" => $news
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $news = News::with('category')->find($id);
  
        if (is_null($news)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product is not found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'News is retrieved successfully.',
            'data' => $news,
        ];
        
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'title' => "required", 'description' => "required", 'image' => "required", 'id_category' => "required", 'links' => "required", 'id_user' => "required", 'status' => "required"
        ]);

        if($validation->fails()){  
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        $news = News::find($id);

        if (is_null($news)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'News is not found!',
            ], 200);
        }

        $news->update($request->all());
        
        $response = [
            'status' => 'success',
            'message' => 'News is updated successfully.',
            'data' => $news,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $news = News::find($id);

        if(is_null($news)){
            return response()->json([
                "status" => "failed",
                "message" => "news not found"
            ], 403);
        }

        try{
            Storage::disk('public')->delete($news->image);
        }catch(\Exception){
            //
        }

        News::destroy($id);
        return response()->json([
            "status" => "success",
            "message" => "delete news successfully",
            "data" => $news
        ], 200);
    }

    public function search($name)
    {
        $news = News::where('title', 'like', '%'. $name . '%')->latest()->get();

        if(is_null($news->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No news found!"
            ], 403);
        }

        $response = [
            "status" => "success",
            "message" => "Get news data successfully",
            "data" => $news
        ];

        return response()->json($response, 200);
    }
}
