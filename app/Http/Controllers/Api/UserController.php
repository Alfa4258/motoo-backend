<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        //get posts
        $users = User::all();

        //return collection of posts as a resource
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        //return single post as a resource
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'role'     => 'required|in:client,teknisi,admin,reporter',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:250',
        ]);

        //check if validation fails
        if ($request->hasFile('photo')) {
            $photoName = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->storeAs('public/user', $photoName);
            $photoUrl = url('storage/user/' . $photoName);
        
         //create user
         $user = User::create([
            'name'      => $request->name,
            'photo'     => $photoUrl,
            'email'     => $request->email,
            'role'      => $request->role,
            'job'       => $request->job,
            'team'      => $request->team,
            'phone'     => $request->phone,
            'password'  => bcrypt($request->password)
        ]);

        //return response
        return new UserResource($user);
        }
    }

    public function update(Request $request, $id)
    {
        // Find the user or fail
        $user = User::findOrFail($id);
    
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'role'     => 'in:client,teknisi,admin,reporter',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:250',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Handle photo update
        if ($request->hasFile('photo')) {
            // Delete the old image if exists
            if ($user->photo) {
                $oldPhoto = str_replace(url('storage'), 'public', $user->photo);
                Storage::delete($oldPhoto);
            }
    
            $photoName = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->storeAs('public/user', $photoName);
            $photoUrl = url('storage/user/' . $photoName);
    
            $user->photo = $photoUrl;
        }
    
        // Update user details
        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'job'       => $request->job,
            'team'      => $request->team,
            'phone'     => $request->phone,
            // Update password only if provided and not empty
            'password'  => $request->filled('password') ? bcrypt($request->password) : $user->password,
        ]);
    
        // Return response
        return new UserResource($user);
    }
    

    public function destroy(User $user)
    {

        //delete post
        $user->delete();

        //return response
        return new UserResource($user);
    }
}