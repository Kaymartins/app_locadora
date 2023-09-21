<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = $this->brand->all();
        return response()->json($brands,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $brand = $this->brand->create($request->all());
        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $brand = $this->brand->find($id);
        if($brand === null){
            return response()->json(['error' => 'Brand not found'], 404);
        }
        return response()->json($brand, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = $this->brand->find($id);
        if($brand === null){
            return response()->json(['error' => 'Could not update. Brand not found'], 404);
        }
        $brand->update($request->all());
        return response()->json($brand, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = $this->brand->find($id);
        if($brand === null){
            return response()->json(['error' => 'Could not delete. Brand not found'], 404);
        }
        $brand->delete();
        return response()->json(['msg' => 'Brand deleted'],200);
    }
}
