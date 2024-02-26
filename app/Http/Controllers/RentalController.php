<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rental = Rental::with('user', 'cars')->latest()->get();

        if(is_null($rental->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No rental found"
            ], 403);
        }

        $response = [
            "status" => "success",
            "messgae" => "Get rental success",
            "data" => $rental
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
            'rental_date' => "required|date", 'return_date' => "required|date|after:rental_date", 'price' => "required", 'user_id' => "required|exists:users,id", 'car_id' => "required|exists:cars,id"
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        $rental = Rental::create($request->all());

        return response()->json([
            "status" => "success",
            "message" => "Rental is added successfully",
            "data" => $rental
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rental = Rental::find($id);
  
        if (is_null($rental)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Rental is not found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'Rental is retrieved successfully.',
            'data' => $rental,
        ];
        
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'rental_date' => "required", 'return_date' => "required", 'price' => "required", 'user_id' => "required", 'car_id' => "required"
        ]);

        if($validation->fails()){  
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validation->errors(),
            ], 403);
        }

        $rental = Rental::find($id);

        if (is_null($rental)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Rental is not found!',
            ], 200);
        }

        $rental->update($request->all());
        
        $response = [
            'status' => 'success',
            'message' => 'Rental is updated successfully.',
            'data' => $rental,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rental = Rental::find($id);

        if(is_null($rental)){
            return response()->json([
                "status" => "failed",
                "message" => "rental not found"
            ], 403);
        }

        Rental::destroy($id);
        return response()->json([
            "status" => "success",
            "message" => "delete rental successfully",
            "data" => $rental
        ], 200);
    }

    public function search($name)
    {
        $rental = Rental::where('rental_date', 'like', '%'. $name . '%')->with('user')->latest()->get();

        if(is_null($rental->first())){
            return response()->json([
                "status" => "failed",
                "message" => "No rental found!"
            ], 403);
        }

        $response = [
            "status" => "success",
            "message" => "Get rental data successfully",
            "data" => $rental
        ];

        return response()->json($response, 200);
    }

    public function getUserRental($id)
    {
        $rentals = Rental::with('user', 'cars')->where('user_id', $id)->get();

        if(is_null($rentals->first())){
            return response()->json([
                "status" => "failed",
                "message" => "Rental not found!",
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "message" => "Get all rental success",
            "data" => $rentals
        ], 200);
    }
}
