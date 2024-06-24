<?php

namespace App\Http\Controllers\Api;

use App\Models\Technology;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TechnologyResource;

class TechnologyController extends Controller
{
    public function index()
    {
        $technologies = Technology::with('applications')->get();
        return TechnologyResource::collection($technologies);
    }
        /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $technology = Technology::with(['applications'])->findOrFail($id);
        return new TechnologyResource($technology);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group' => 'required',
            'name' => 'required',
            'version' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();

        $technology = Technology::create([
            'group' => $request->group,
            'name' => $request->name,
            'version' => $request->version,
            'created_by' => $user->id, 
            'updated_by' => $user->id,  
        ]);

        return new TechnologyResource($technology);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technology $technology)
    {
        $technology->update($request->all());

        return new TechnologyResource($technology);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technology $technology)
    {
        $technology->delete();
        return new TechnologyResource($technology);
    }
}
