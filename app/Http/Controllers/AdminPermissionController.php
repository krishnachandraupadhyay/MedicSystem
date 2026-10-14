<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\MedicalType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPermissionController extends Controller
{
    /**
     * Authorize Super Admin only
     */
    protected function authorizeSuperAdmin(): void
    {
        if (Auth::user()->role !== 'Super Admin') {
            abort(403, 'Unauthorized: Only Super Admin can access Admin Permissions.');
        }
    }

    /**
     * Display list of admin facilities and permission controls.
     */
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $search = $request->query('search');
        $medicalTypeFilter = $request->query('medical_type');
        $statusFilter = $request->query('status');

        $query = Hospital::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($medicalTypeFilter && $medicalTypeFilter !== 'all') {
            $query->where('medical_type', $medicalTypeFilter);
        }

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $hospitals = $query->orderBy('id', 'asc')->get();
        $medicalTypes = MedicalType::where('status', 'Active')->get();

        return view('dashboard.admin_permissions', compact('hospitals', 'search', 'medicalTypeFilter', 'statusFilter', 'medicalTypes'));
    }

    /**
     * Update permissions for a specific hospital / admin.
     */
    public function update(Request $request, Hospital $hospital)
    {
        $this->authorizeSuperAdmin();
        $hospital->enable_laboratory = $request->boolean('enable_laboratory');
        $hospital->enable_multi_branch = $request->boolean('enable_multi_branch');
        if ($request->has('enable_pharmacy')) {
            $hospital->enable_pharmacy = $request->boolean('enable_pharmacy');
        }
        $hospital->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully!',
                'hospital' => $hospital,
            ]);
        }

        return back()->with('success', 'Permissions updated successfully for '.$hospital->name.'!');
    }

    /**
     * Fast toggle a single permission switch via AJAX.
     */
    public function toggle(Request $request, Hospital $hospital)
    {
        $this->authorizeSuperAdmin();

        $permission = $request->input('permission');

        if (! in_array($permission, ['enable_laboratory', 'enable_multi_branch', 'enable_pharmacy'])) {
            return response()->json(['success' => false, 'message' => 'Invalid permission parameter.'], 422);
        }

        $hospital->{$permission} = ! $hospital->{$permission};
        $hospital->save();

        return response()->json([
            'success' => true,
            'permission' => $permission,
            'value' => (bool) $hospital->{$permission},
            'message' => ucwords(str_replace('_', ' ', $permission)).' has been '.($hospital->{$permission} ? 'Enabled' : 'Disabled').' for '.$hospital->name.'.',
            'hospital' => $hospital,
        ]);
    }
}
