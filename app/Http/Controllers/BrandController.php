<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;


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
    public function store(StoreBrandRequest $request)
    {
        $request->validated();
        $brand_picture = $request->file('picture');
        $brand_picture_urn = $brand_picture->store('images/brands', 'public');

        $brand = $this->brand->create([
            'name' => $request->name,
            'picture' => $brand_picture_urn
        ]);

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
    public function update(UpdateBrandRequest $request, $id)
    {
        $brand = $this->brand->find($id);
        if($brand === null){
            return response()->json(['error' => 'Could not update. Brand not found'], 404);
        }

        if($request->file('picture')){
            Storage::disk('public')->delete($brand->picture);
        }

        if($request->method() === 'PATCH'){
            $dynamicRules = [];
            foreach($request->rules() as $input => $rule){
                if(array_key_exists($input, $request->all())){
                    $dynamicRules[$input] = $rule;
                }
            }
            $request->validate($dynamicRules);
        }else{
            $brand->update($request->validated());
        }

        $brand_picture = $request->file('picture');
        $brand_picture_urn = $brand_picture->store('images/brands', 'public');

        $brand->update([
            'picture' => $brand_picture_urn
        ]);

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

        Storage::disk('public')->delete($brand->picture);

        $brand->delete();
        return response()->json(['msg' => 'Brand deleted'],200);
    }
}
