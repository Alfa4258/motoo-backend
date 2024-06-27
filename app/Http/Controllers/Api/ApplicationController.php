<?php

namespace App\Http\Controllers\Api;

use App\Models\Pic;
use App\Models\Topology;
use App\Models\Company;
use App\Models\GroupArea;
use App\Models\Technology;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\VirtualMachine;
use App\Imports\ApplicationImport;
use App\Exports\ApplicationsExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ApplicationResource;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with([
            'virtual_machines', 
            'topologies', 
            'technologies', 
            'pics',
            'groupArea',
            'companies',
            'reviews',
        ])->get();

        return ApplicationResource::collection($applications);
    }

    public function show($id)
    {
        // Retrieve the application by its ID with eager loading of related data
        $application = Application::with([
            'virtual_machines', 
            'topologies', 
            'technologies', 
            'pics',
            'groupArea',
            'companies',
            'reviews',
        ])->findOrFail($id);
    
        // Return the single application as a resource
        return new ApplicationResource($application);
    }

    public function store(Request $request)
    {
        // Normalize the input
        $request->merge([
            'platform' => strtolower($request->input('platform')),
            'status' => strtolower($request->input('status')),
            'category' => strtolower($request->input('category')),
            'user_login' => strtolower($request->input('user_login'))
        ]);
    
        // Validate the request
        $validator = Validator::make($request->all(), [
            'short_name' => 'required|unique:applications',
            'long_name' => 'required|unique:applications',
            'description' => 'required',
            'business_process' => 'required',
            'platform' => 'required|in:mobile,dekstop,website',
            'status' => 'required|in:up,down,maintenance,delete',
            'tier' => 'required|in:1,2,3,4,5,6,7,8,9,10',
            'category' => 'required|in:sap,non_sap,turunan,ot/it,collaboration',
            'image' => 'image|mimes:png,jpg,jpeg|max:150',
            'db_connection_path' => 'required',
            'ad_connection_path' => 'required',
            'sap_connection_path' => 'required',
            'technical_doc' => 'required',
            'user_login' => 'required|in:login sso,login ad,internal apps',
            'user_doc' => 'required',
            'other_doc' => 'required',
            'information' => 'required',
            'vm_prod' => 'required',
            'vm_dev' => 'required',
            'url_prod' => 'required',
            'url_dev' => 'required',
            'environment' => 'required|in:production,development,testing',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Store the image in public/storage/application
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/application', $imageName);
            $imageUrl = url('storage/application/' . $imageName);
        }
    
        // Retrieve related entities
        $groupArea = GroupArea::find($request->input('group_area')) ?: GroupArea::where('short_name', $request->input('group_area'))->first();
        $productBy = Company::find($request->input('product_by')) ?: Company::where('short_name', $request->input('product_by'))->first();
    
        // Create the application
        $application = Application::with(['groupArea', 'companies'])->create([
            'short_name' => $request->input('short_name'),
            'long_name' => $request->input('long_name'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'image' => $imageUrl,
            'platform' => $request->input('platform'),
            'category' => $request->input('category'),
            'tier' => $request->input('tier'),
            'user_login' => $request->input('user_login'),
            'product_by' => $productBy->id,
            'group_area' => $groupArea->id,
            'business_process' => $request->input('business_process'),
            'db_connection_path' => $request->input('db_connection_path'),
            'ad_connection_path' => $request->input('ad_connection_path'),
            'sap_connection_path' => $request->input('sap_connection_path'),
            'technical_doc' => $request->input('technical_doc'),
            'user_doc' => $request->input('user_doc'),
            'other_doc' => $request->input('other_doc'),
            'information' => $request->input('information'),
            'vm_prod' => $request->input('vm_prod'),
            'vm_dev' => $request->input('vm_dev'),
            'url_prod' => $request->input('url_prod'),
            'url_dev' => $request->input('url_dev'),
        ]);
    
        // Attach related entities
        $virtual_machines = $request->input('virtual_machines', []);
        foreach ($virtual_machines as $vm) {
            $virtual_machine = VirtualMachine::where('id', $vm)->orWhere('name', $vm)->first();
            if ($virtual_machine) {
                $application->virtual_machines()->attach($virtual_machine, ['environment' => $request->input('environment')]);
            }
        }
    
        $topologies = $request->input('topologies', []);
        foreach ($topologies as $topologyInput) {
            $topology = Topology::where('id', $topologyInput)->orWhere('group', $topologyInput)->first();
            if ($topology) {
                $application->topologies()->attach($topology);
            }
        }
    
        $technologies = $request->input('technologies', []);
        foreach ($technologies as $technologyInput) {
            $technology = Technology::where('id', $technologyInput)->orWhere('name', $technologyInput)->first();
            if ($technology) {
                $application->technologies()->attach($technology);
            }
        }
    
        $this->attachPic($request->input('old_pic', []), 'old_pic', $application);
        $this->attachPic($request->input('first_pic', []), 'first_pic', $application);
        $this->attachPic($request->input('backup_pic', []), 'backup_pic', $application);
        $this->attachPic($request->input('pic_ict', []), 'pic_ict', $application);
        $this->attachPic($request->input('pic_user', []), 'pic_user', $application);
    
        return new ApplicationResource($application);
    }
    
    private function attachPic($picInputs, $picType, $application)
    {
        if (!is_array($picInputs)) {
            $picInputs = [$picInputs];
        }
    
        foreach ($picInputs as $picInput) {
            if ($picInput) {
                // Check if input is numeric (assuming ID)
                if (is_numeric($picInput)) {
                    $pic = Pic::find($picInput);
                } else { // Otherwise, assume it's a name
                    $pic = Pic::where('name', $picInput)->first();
                }
    
                if ($pic) {
                    // Check if the pic is already attached to avoid duplication
                    if (!$application->pics()->where('pic_id', $pic->id)->wherePivot('pic_type', $picType)->exists()) {
                        $application->pics()->attach($pic->id, ['pic_type' => $picType]);
                    }
                }
            }
        }
    }
    
    public function update(Request $request, $id)
    {
        // Normalize the input
        $request->merge([
            'platform' => strtolower($request->input('platform')),
            'status' => strtolower($request->input('status')),
            'category' => strtolower($request->input('category')),
            'user_login' => strtolower($request->input('user_login'))
        ]);
    
        // Validate the request
        $validator = Validator::make($request->all(), [
            'short_name' => 'required|unique:applications,short_name,' . $id,
            'long_name' => 'required|unique:applications,long_name,' . $id,
            'description' => 'required',
            'business_process' => 'required',
            'platform' => 'required|in:mobile,dekstop,website',
            'status' => 'required|in:up,down,maintenance,delete',
            'tier' => 'required|in:1,2,3,4,5,6,7,8,9,10',
            'category' => 'required|in:sap,non_sap,turunan,ot/it,collaboration',
            'image' => 'image|mimes:png,jpg,jpeg|max:150',
            'db_connection_path' => 'required',
            'ad_connection_path' => 'required',
            'sap_connection_path' => 'required',
            'technical_doc' => 'required',
            'user_login' => 'required|in:login sso,login ad,internal apps',
            'user_doc' => 'required',
            'other_doc' => 'required',
            'information' => 'required',
            'vm_prod' => 'required',
            'vm_dev' => 'required',
            'url_prod' => 'required',
            'url_dev' => 'required',
            'environment' => 'required|in:production,development,testing',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $application = Application::findOrFail($id);
    
        // Store the image in public/storage/application
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($application->image) {
                $oldImage = str_replace(url('storage'), 'public', $application->image);
                Storage::delete($oldImage);
            }
    
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/application', $imageName);
            $imageUrl = url('storage/application/' . $imageName);
            $application->image = $imageUrl;
        }
    
        // Retrieve related entities
        $groupArea = GroupArea::find($request->input('group_area')) ?: GroupArea::where('short_name', $request->input('group_area'))->first();
        $productBy = Company::find($request->input('product_by')) ?: Company::where('short_name', $request->input('product_by'))->first();
    
        // Update the application
        $application->update([
            'short_name' => $request->input('short_name'),
            'long_name' => $request->input('long_name'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'platform' => $request->input('platform'),
            'category' => $request->input('category'),
            'tier' => $request->input('tier'),
            'user_login' => $request->input('user_login'),
            'product_by' => $productBy->id,
            'group_area' => $groupArea->id,
            'business_process' => $request->input('business_process'),
            'db_connection_path' => $request->input('db_connection_path'),
            'ad_connection_path' => $request->input('ad_connection_path'),
            'sap_connection_path' => $request->input('sap_connection_path'),
            'technical_doc' => $request->input('technical_doc'),
            'user_doc' => $request->input('user_doc'),
            'other_doc' => $request->input('other_doc'),
            'information' => $request->input('information'),
            'vm_prod' => $request->input('vm_prod'),
            'vm_dev' => $request->input('vm_dev'),
            'url_prod' => $request->input('url_prod'),
            'url_dev' => $request->input('url_dev'),
        ]);
    
        // Detach and re-attach related entities
        $application->virtual_machines()->detach();
        $virtual_machines = $request->input('virtual_machines', []);
        foreach ($virtual_machines as $vm) {
            $virtual_machine = VirtualMachine::where('id', $vm)->orWhere('name', $vm)->first();
            if ($virtual_machine) {
                $application->virtual_machines()->attach($virtual_machine, ['environment' => $request->input('environment')]);
            }
        }
    
        $application->topologies()->detach();
        $topologies = $request->input('topologies', []);
        foreach ($topologies as $topologyInput) {
            $topology = Topology::where('id', $topologyInput)->orWhere('group', $topologyInput)->first();
            if ($topology) {
                $application->topologies()->attach($topology);
            }
        }
    
        $application->technologies()->detach();
        $technologies = $request->input('technologies', []);
        foreach ($technologies as $technologyInput) {
            $technology = Technology::where('id', $technologyInput)->orWhere('name', $technologyInput)->first();
            if ($technology) {
                $application->technologies()->attach($technology);
            }
        }
    
        $this->updatePics($request->input('old_pic', []), 'old_pic', $application);
        $this->updatePics($request->input('first_pic', []), 'first_pic', $application);
        $this->updatePics($request->input('backup_pic', []), 'backup_pic', $application);
        $this->updatePics($request->input('pic_ict', []), 'pic_ict', $application);
        $this->updatePics($request->input('pic_user', []), 'pic_user', $application);
    
        return new ApplicationResource($application);
    }
    
    private function updatePics(Request $request, $picType, $application, $isFirstPic = false)
    {
        $picInput = $request->input($picType, []);
    
        if ($isFirstPic && $picInput) {
            // Move current first_pic to old_pic
            $currentFirstPics = $application->pics()->wherePivot('pic_type', 'first_pic')->get();
            foreach ($currentFirstPics as $currentFirstPic) {
                // Check if the pic is already attached as an old_pic to avoid duplication
                if (!$application->pics()->where('pic_id', $currentFirstPic->id)->wherePivot('pic_type', 'old_pic')->exists()) {
                    $application->pics()->attach($currentFirstPic->id, ['pic_type' => 'old_pic']);
                }
            }
        }
    
        $this->syncPic($picInput, $picType, $application);
    }
    
    public function destroy(Application $application)
    {
        Storage::delete('public/posts/' . $application->image);
        $application->delete();
        return new ApplicationResource($application);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);
    
        Excel::import(new ApplicationImport, request()->file('file'));
    
        return response()->json(['message' => 'Import successful'], 200);
    }
    // public function export()
    // {
    //     return Excel::download(new ApplicationsExport, 'applications.xlsx');
    // }
}
