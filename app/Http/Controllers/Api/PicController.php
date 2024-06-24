<?php

namespace App\Http\Controllers\Api;

use App\Models\Pic;
use Illuminate\Http\Request;
use App\Http\Resources\PicResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;   

class PicController extends Controller
{
    public function index()
    {
        $pics = Pic::with('applications','user')->get();
        return PicResource::collection($pics);
    }

    public function show($id)
    {
        $pic = Pic::with(['applications', 'user'])->findOrFail($id);
        return new PicResource($pic);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact' => 'required|string',
            'jobdesc' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:250',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photo = time() . '.' . $request->photo->getClientOriginalExtension();
        $request->photo->move(public_path('images/pic'), $photo);

        $pic = Pic::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'jobdesc' => $request->jobdesc,
            'photo' => $photo,
            'status' => $request->status,
        ]);

        return (new PicResource($pic))->additional(['message' => 'Berhasil Ditambahkan!']);
    }

    public function update(Request $request, Pic $pic)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:250',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            $oldPhotoPath = public_path('images/pic/') . $pic->photo;
            if (File::exists($oldPhotoPath)) {
                File::delete($oldPhotoPath);
            }
            
            // Store new photo
            $photoName = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('images/pic'), $photoName);
            $pic->photo = $photoName;
        }
    
        // Update other fields if any
        $pic->update($request->except('photo')); // Menghindari kolom 'photo' dalam array yang dikirim untuk update
    
        return (new PicResource($pic))->additional(['message' => 'Berhasil Diperbarui!']);
    }
    

    public function destroy(Pic $pic)
    {
        Storage::delete('public/posts/' . $pic->photo);
        $pic->delete();
        return response()->json(['message' => 'Berhasil Dihapus!']);
    }
}