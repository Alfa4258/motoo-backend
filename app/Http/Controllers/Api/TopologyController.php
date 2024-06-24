<?php

namespace App\Http\Controllers\Api;

use App\Models\Topology;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TopologyResource;
use Illuminate\Support\Facades\Validator;

class TopologyController extends Controller
{
    public function index()
    {
        $topologies = Topology::with('applications')->get();
        return TopologyResource::collection($topologies);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $topology = Topology::with(['applications'])->findOrFail($id);
        return new TopologyResource($topology);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'status' => strtolower($request->input('status')),
        ]);
        $validator = Validator::make($request->all(), [
            'group' => 'required',
            'link' => 'required',
            'description' => 'required',
            'status' =>  'required|in:used,not used',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();
        $topology = Topology::create([
            'group' => $request->group,
            'link' => $request->link,
            'description' => $request->description,
            'status' => $request->status,
            'created_by' => $user->id, 
            'updated_by' => $user->id,  
        ]);

        return new TopologyResource($topology);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $topology = Topology::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'group' => 'required',
            'link' => 'required',
            'description' => 'required',
            'status' =>  'required|in:used, not used',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $user = Auth::user();
    
        $topology->update([
            'group' => $request->group,
            'link' => $request->link,
            'description' => $request->description,
            'status' => $request->status,
            'updated_by' => $user->id,
        ]);
    
        return new TopologyResource($topology);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $topology = Topology::findOrFail($id);
        $topology->delete();
    
        return new TopologyResource($topology);
    }
}
