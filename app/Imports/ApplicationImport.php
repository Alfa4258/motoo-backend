<?php

namespace App\Imports;

use App\Models\Pic;
use App\Models\Company;
use App\Models\Topology;
use App\Models\GroupArea;
use App\Models\Technology;
use App\Models\Application;
use App\Models\VirtualMachine;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ApplicationImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                // Transform data to lowercase
                $row['platform'] = strtolower($row['platform']);
                $row['status'] = strtolower($row['status']);
                $row['category'] = strtolower($row['category']);
                $row['user_login'] = strtolower($row['user_login']);

                // Validate data
                $validator = Validator::make($row->toArray(), [
                    'name' => 'required|unique:applications,name,' . $row['name'] . ',name',
                    'description' => 'required',
                    'business_process' => 'required',
                    'platform' => 'required|in:mobile,dekstop,website',
                    'status' => 'required|in:up,down,maintenance,delete',
                    'tier' => 'required|in:1,2,3,4,5,6,7,8,9,10',
                    'category' => 'required|in:sap,non_sap,turunan,ot/it,collaboration',
                    'db_connection_path' => 'required',
                    'ad_connection_path' => 'required',
                    'sap_connection_path' => 'required',
                    'user_login' => 'required|in:login sso,login ad,internal apps',
                    'technical_doc' => 'required',
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
                    // Log validation errors and skip the row
                    Log::error('Validation failed for row: ' . json_encode($row));
                    Log::error('Validation errors: ' . json_encode($validator->errors()));
                    continue;
                }

                // Import row data
                try {
                    $this->importRow($row);
                } catch (\Exception $e) {
                    Log::error('Failed to import row: ' . json_encode($row));
                    Log::error('Error: ' . $e->getMessage());
                }
            }
        });
    }

    protected function importRow($row)
    {
        $groupArea = GroupArea::find($row['group_area']) ?: GroupArea::where('name', $row['group_area'])->first();
        $productBy = Company::find($row['product_by']) ?: Company::where('name', $row['product_by'])->first();

        if (!$groupArea || !$productBy) {
            Log::error('GroupArea or ProductBy not found for row: ' . json_encode($row));
            return;
        }

        // Check if the application already exists
        $application = Application::where('name', $row['name'])->first();

        $applicationData = [
            'description' => $row['description'],
            'status' => $row['status'],
            'platform' => $row['platform'],
            'category' => $row['category'],
            'tier' => $row['tier'],
            'business_process' => $row['business_process'],
            'group_area' => $groupArea->id,
            'product_by' => $productBy->id,
            'db_connection_path' => $row['db_connection_path'],
            'ad_connection_path' => $row['ad_connection_path'],
            'sap_connection_path' => $row['sap_connection_path'],
            'vm_prod' => $row['vm_prod'],
            'vm_dev' => $row['vm_dev'],
            'url_dev' => $row['url_dev'],
            'url_prod' => $row['url_prod'],
            'information' => $row['information'],
            'user_login' => $row['user_login'],
            'technical_doc' => $row['technical_doc'],
            'user_doc' => $row['user_doc'],
            'other_doc' => $row['other_doc'],            
        ];

        if ($application) {
            // Update the existing application
            $application->update($applicationData);
        } else {
            // Create a new application
            $application = Application::create(array_merge(['name' => $row['name']], $applicationData));
        }

        if (!$application) {
            Log::error('Failed to create/update application for row: ' . json_encode($row));
            return;
        }

        // Attach relationships
        $this->attachRelatedEntities($application, $row);
    }

    private function attachRelatedEntities($application, $row)
    {
        // Attach Virtual Machines
        if (!empty($row['virtual_machines'])) {
            $virtualMachines = explode(',', $row['virtual_machines']);
            foreach ($virtualMachines as $vm) {
                $virtualMachine = VirtualMachine::where('id', trim($vm))->orWhere('name', trim($vm))->first();
                if ($virtualMachine) {
                    $application->virtual_machines()->syncWithoutDetaching([$virtualMachine->id => ['environment' => $row['environment']]]);
                } else {
                    Log::error('VirtualMachine not found: ' . $vm);
                }
            }
        }

        // Attach Topologies
        if (!empty($row['topologies'])) {
            $topologies = explode(',', $row['topologies']);
            foreach ($topologies as $topologyInput) {
                $topology = Topology::where('id', trim($topologyInput))->orWhere('group', trim($topologyInput))->first();
                if ($topology) {
                    $application->topologies()->syncWithoutDetaching([$topology->id]);
                } else {
                    Log::error('Topology not found: ' . $topologyInput);
                }
            }
        }

        // Attach Technologies
        if (!empty($row['technologies'])) {
            $technologies = explode(',', $row['technologies']);
            foreach ($technologies as $technologyInput) {
                $technology = Technology::where('id', trim($technologyInput))->orWhere('name', trim($technologyInput))->first();
                if ($technology) {
                    $application->technologies()->syncWithoutDetaching([$technology->id]);
                } else {
                    Log::error('Technology not found: ' . $technologyInput);
                }
            }
        }

        // Attach Pics
        $this->syncPic($row['old_pic'], 'old_pic', $application);
        $this->syncPic($row['first_pic'], 'first_pic', $application);
        $this->syncPic($row['backup_pic'], 'backup_pic', $application);
        $this->syncPic($row['pic_ict'], 'pic_ict', $application);
        $this->syncPic($row['pic_user'], 'pic_user', $application);
    }

    private function syncPic($picInput, $picType, $application)
    {
        if (!empty($picInput)) {
            $pics = explode(',', $picInput);
            foreach ($pics as $picValue) {
                if (is_numeric($picValue)) {
                    $pic = Pic::find($picValue);
                } else {
                    $pic = Pic::where('name', trim($picValue))->first();
                }
                if ($pic) {
                    if (!$application->pics()->where('pic_id', $pic->id)->wherePivot('pic_type', $picType)->exists()) {
                        $application->pics()->attach($pic->id, ['pic_type' => $picType]);
                    }
                } else {
                    Log::error('Pic not found: ' . $picValue);
                }
            }
        }
    }
}
