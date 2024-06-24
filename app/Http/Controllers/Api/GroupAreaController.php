<?php

namespace App\Http\Controllers\Api;

use App\Models\GroupArea;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\GroupAreaResource;
use Illuminate\Support\Facades\Validator;

class GroupAreaController extends Controller
{
    public function index()
    {
        $group_areas = GroupArea::with(['applications'])->get();
        return GroupAreaResource::collection($group_areas);
    }

    public function show($id)
    {
        $group_area = GroupArea::with(['applications'])->findOrFail($id);
        return new GroupAreaResource($group_area);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'short_name' => 'required',
            'long_name' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/group', $imageName);
            $imageUrl = url('storage/group/' . $imageName);
    
            $group_area = GroupArea::create([
                'short_name' => $request->short_name,
                'long_name' => $request->long_name,
                'image' => $imageUrl,
            ]);
    
            return new GroupAreaResource($group_area);} 
            else 
            { 
                return response()->json(['error' => 'Logo file is required'], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'short_name' => 'required',
            'long_name' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg|max:250', // image not required for update
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $group_area = GroupArea::findOrFail($id);
    
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($group_area->image) {
                $oldImage = str_replace(url('storage'), 'public', $group_area->image);
                Storage::delete($oldImage);
            }
    
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/group', $imageName);
            $imageUrl = url('storage/group/' . $imageName);
    
            $group_area->image = $imageUrl;
        }
    
        $group_area->short_name = $request->short_name;
        $group_area->long_name = $request->long_name;
        $group_area->save();
    
        return new GroupAreaResource($group_area);
    }
    
    public function destroy(GroupArea $group_area)
    {
        $group_area->delete();
        return new GroupAreaResource($group_area);
    }
}
