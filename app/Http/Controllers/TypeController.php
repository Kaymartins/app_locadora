<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use App\Models\Type;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = Type::with('brand')->get();
        return response()->json($types, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeRequest $request)
    {
        $request->validated();
        $type_picture = $request->file('picture');
        $type_picture_urn = $type_picture->store('images/types', 'public');

        $type = Type::create([
            'name' => $request->name,
            'picture' => $type_picture_urn,
            'doors_number' => $request->doors_number,
            'seats_number' => $request->seats_number,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
            'brand_id' => $request->brand_id,
        ]);

        return response()->json($type, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $type= Type::with('brand')->find($id);
        if($type === null){
            return response()->json(['error' => 'Type not found'], 404);
        }

        return response()->json($type, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, $id)
    {

        $type = Type::find($id);

        if($type === null)
        {
            return response()->json(['error' => 'Could not update. Type not found'], 404);
        }

        if($request->file('picture'))
        {
            Storage::disk('public')->delete($type->picture);
        }

        if($request->method() === 'PATCH'){
            $dynamicRules = [];
            foreach($request->rules() as $input => $rule){
                if(array_key_exists($input, $request->all())){
                    $dynamicRules[$input] = $rule;
                }
            }
            $type->update($request->validate($dynamicRules));
        }else {
            $type->update($request->validated());
        }

        $type_picture = $request->file('picture');
        $type_picture_urn = $type_picture->store('images/types', 'public');

        $type->update([
            'picture' => $type_picture_urn,
        ]);

        return response()->json($type, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $type = Type::find($id);
        if($type === null) {
            return response()->json(['error' => 'Could not delete. Type not found']);
        }

        Storage::disk('public')->delete($type->picture);

        $type->delete();
        return response()->json(['msg' => 'Type deleted']);
    }
}
