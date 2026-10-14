<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\HospitalType;
use App\Models\LaboratoryType;
use App\Models\MedicalType;
use App\Models\PharmacyType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * 1st Navigation: Dashboard Overview
     */
    public function index()
    {
        $stats = [
            'total_hospitals' => Hospital::count(),
            'active_hospitals' => Hospital::where('status', 'Active')->count(),
            'total_admins' => User::count(),
            'active_admins' => User::where('status', 'Active')->count(),
        ];

        $hospitals = Hospital::orderBy('id', 'asc')->get();

        return view('dashboard.overview', compact('stats', 'hospitals'));
    }

    /**
     * Authorize Super Admin / Admin only
     */
    protected function authorizeAdminManagement(): void
    {
        $role = Auth::user()?->role;
        if (! in_array($role, ['Super Admin', 'Admin'])) {
            abort(403, 'Unauthorized: Only Super Admin can access Hospital/Admin Management.');
        }
    }

    /**
     * 2nd Navigation: Admin -> Hospital List Page
     */
    public function hospitals(Request $request)
    {
        $this->authorizeAdminManagement();

        $search = $request->query('search');

        $query = Hospital::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $hospitals = $query->orderBy('id', 'asc')->get();
        $medicalTypes = MedicalType::where('status', 'Active')->get();
        $hospitalTypes = HospitalType::where('status', 'Active')->get();
        $laboratoryTypes = LaboratoryType::where('status', 'Active')->get();
        $pharmacyTypes = PharmacyType::where('status', 'Active')->get();
        $existingHospitals = Hospital::where(function ($q) {
            $q->where('medical_type', 'Hospital')
                ->orWhereNull('medical_type');
        })->orderBy('name', 'asc')->get(['id', 'name', 'type', 'city']);

        return view('dashboard.hospitals', compact('hospitals', 'search', 'medicalTypes', 'hospitalTypes', 'laboratoryTypes', 'pharmacyTypes', 'existingHospitals'));
    }

    /**
     * Store new Hospital
     */
    public function storeHospital(Request $request)
    {
        $this->authorizeAdminManagement();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'digits:10'],
            'city' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'address' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'medical_type' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:4'],
            'status' => ['required', 'string'],
            'affiliated_hospital_select' => ['nullable', 'string'],
            'affiliated_hospital_id' => ['nullable'],
            'affiliated_hospital_name' => ['nullable', 'string', 'max:255'],
            'affiliated_hospital_custom_name' => ['nullable', 'string', 'max:255'],
        ], [
            'city.regex' => 'City name must only contain letters and spaces (no numbers allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        if (empty($validated['medical_type'])) {
            $validated['medical_type'] = 'Hospital';
        }

        if (empty($validated['role'])) {
            $validated['role'] = 'Admin';
        }

        $isHospitalPharmacy = (
            ($validated['medical_type'] ?? '') === 'Pharmacy' &&
            strcasecmp($validated['type'] ?? '', 'Hospital Pharmacy') === 0
        );

        if ($isHospitalPharmacy) {
            $selectVal = $request->input('affiliated_hospital_select');
            $customName = trim($request->input('affiliated_hospital_custom_name', ''));

            if ($selectVal === 'other' || ! empty($customName)) {
                $validated['affiliated_hospital_id'] = null;
                $validated['affiliated_hospital_name'] = $customName ?: $request->input('affiliated_hospital_name');
            } elseif (is_numeric($selectVal)) {
                $parentHosp = Hospital::find($selectVal);
                if ($parentHosp) {
                    $validated['affiliated_hospital_id'] = $parentHosp->id;
                    $validated['affiliated_hospital_name'] = $parentHosp->name;
                } else {
                    $validated['affiliated_hospital_id'] = null;
                    $validated['affiliated_hospital_name'] = null;
                }
            } else {
                $validated['affiliated_hospital_id'] = null;
                $validated['affiliated_hospital_name'] = null;
            }
        } else {
            $validated['affiliated_hospital_id'] = null;
            $validated['affiliated_hospital_name'] = null;
        }

        unset($validated['affiliated_hospital_select'], $validated['affiliated_hospital_custom_name']);

        $maxId = Hospital::max('id') ?? 0;
        $validated['code'] = 'HOSP-'.str_pad($maxId + 1, 2, '0', STR_PAD_LEFT);

        $hospital = Hospital::create($validated);

        if (! empty($validated['email']) && ! empty($validated['password'])) {
            User::updateOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'hospital_name' => $validated['name'],
                    'hospital_id' => $hospital->id,
                    'password' => $validated['password'],
                    'role' => ($validated['medical_type'] === 'Laboratory') ? 'Laboratory Admin' : ($validated['role'] ?? 'Admin'),
                    'status' => $validated['status'] ?? 'Active',
                    'phone' => $validated['phone'] ?? null,
                ]
            );
        }

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/addadmin")->with('success', 'Admin facility added successfully!');
    }

    /**
     * Update existing Hospital
     */
    public function updateHospital(Request $request, Hospital $hospital)
    {
        $this->authorizeAdminManagement();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'digits:10'],
            'city' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'address' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'medical_type' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'affiliated_hospital_select' => ['nullable', 'string'],
            'affiliated_hospital_id' => ['nullable'],
            'affiliated_hospital_name' => ['nullable', 'string', 'max:255'],
            'affiliated_hospital_custom_name' => ['nullable', 'string', 'max:255'],
        ], [
            'city.regex' => 'City name must only contain letters and spaces (no numbers allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        if (empty($validated['medical_type'])) {
            $validated['medical_type'] = 'Hospital';
        }

        $isHospitalPharmacy = (
            ($validated['medical_type'] ?? '') === 'Pharmacy' &&
            strcasecmp($validated['type'] ?? '', 'Hospital Pharmacy') === 0
        );

        if ($isHospitalPharmacy) {
            $selectVal = $request->input('affiliated_hospital_select');
            $customName = trim($request->input('affiliated_hospital_custom_name', ''));

            if ($selectVal === 'other' || ! empty($customName)) {
                $validated['affiliated_hospital_id'] = null;
                $validated['affiliated_hospital_name'] = $customName ?: $request->input('affiliated_hospital_name');
            } elseif (is_numeric($selectVal)) {
                $parentHosp = Hospital::find($selectVal);
                if ($parentHosp) {
                    $validated['affiliated_hospital_id'] = $parentHosp->id;
                    $validated['affiliated_hospital_name'] = $parentHosp->name;
                } else {
                    $validated['affiliated_hospital_id'] = null;
                    $validated['affiliated_hospital_name'] = null;
                }
            } else {
                $validated['affiliated_hospital_id'] = null;
                $validated['affiliated_hospital_name'] = null;
            }
        } else {
            $validated['affiliated_hospital_id'] = null;
            $validated['affiliated_hospital_name'] = null;
        }

        unset($validated['affiliated_hospital_select'], $validated['affiliated_hospital_custom_name']);

        $passwordToUpdate = $validated['password'] ?? null;
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $hospital->update($validated);

        if (! empty($validated['email'])) {
            $userData = [
                'name' => $validated['name'],
                'hospital_name' => $validated['name'],
                'hospital_id' => $hospital->id,
                'role' => ($validated['medical_type'] === 'Laboratory') ? 'Laboratory Admin' : ($validated['role'] ?? 'Admin'),
                'status' => $validated['status'] ?? 'Active',
                'phone' => $validated['phone'] ?? null,
            ];
            if ($passwordToUpdate) {
                $userData['password'] = $passwordToUpdate;
            }
            User::updateOrCreate(['email' => $validated['email']], $userData);
        }

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/addadmin")->with('success', 'Admin facility updated successfully!');
    }

    /**
     * Toggle Hospital Active/Inactive Status
     */
    public function toggleHospitalStatus(Hospital $hospital)
    {
        $this->authorizeAdminManagement();

        $hospital->status = ($hospital->status === 'Active') ? 'Inactive' : 'Active';
        $hospital->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $hospital->status,
                'message' => 'Status updated to '.$hospital->status,
            ]);
        }

        return back()->with('success', 'Status updated successfully!');
    }

    /**
     * Delete Hospital
     */
    public function destroyHospital(Hospital $hospital)
    {
        $this->authorizeAdminManagement();

        $hospital->delete();

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/addadmin")->with('success', 'Admin facility deleted successfully!');
    }
}
