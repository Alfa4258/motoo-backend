<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\VirtualMachine;
use App\Models\Application;
use App\Http\Controllers\Controller;

class EnvironmentController extends Controller
{
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'app_id' => 'required|exists:applications,id',
            'vm_id' => 'required|exists:virtual_machines,id',
            'environment' => 'required|in:production,development,testing',
        ]);
        
        // Temukan aplikasi dan mesin virtual yang sesuai
        $app = Application::find($request->input('app_id'));
        $virtualMachine = VirtualMachine::find($request->input('vm_id'));
        
        // Periksa apakah hubungan sudah ada
        if ($app->virtual_machines->contains($virtualMachine)) {
        // Jika hubungan sudah ada, perbarui lingkungan di pivot table
        $app->virtual_machines()->updateExistingPivot($virtualMachine->id, ['environment' => $request->input('environment')]);
        $message = 'Environment berhasil diperbarui';
        } else {
        // Jika hubungan belum ada, tambahkan hubungan baru dengan lingkungan yang sesuai
        $app->virtual_machines()->attach($virtualMachine, ['environment' => $request->input('environment')]);
        $message = 'Environment berhasil ditambahkan';
        }
        
        // Response sukses dengan data sumber daya yang diperbarui
        return response()->json([
            'message' => 'Environment berhasil diperbarui',
        ], 200);
    }
}
