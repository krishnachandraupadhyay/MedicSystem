<?php

namespace App\Http\Controllers;

use App\Models\HospitalType;
use App\Models\LaboratoryType;
use App\Models\MedicalType;
use App\Models\PharmacyType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            function ($request, $next) {
                if (Auth::user()?->role !== 'Super Admin') {
                    abort(403, 'Unauthorized: Only Super Admin can access Master Data.');
                }

                return $next($request);
            },
        ];
    }

    /**
     * 1. MEDICAL TYPES
     */
    public function medicalTypes(Request $request)
    {
        $search = $request->query('search');
        $query = MedicalType::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('id', 'asc')->get();

        return view('master.medical_types', compact('items', 'search'));
    }

    public function storeMedicalType(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        if (empty($validated['code'])) {
            $maxId = MedicalType::max('id') ?? 0;
            $validated['code'] = 'MED-'.str_pad($maxId + 1, 2, '0', STR_PAD_LEFT);
        }

        MedicalType::create($validated);

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/medical-types")->with('success', 'Medical Type added successfully!');
    }

    public function updateMedicalType(Request $request, MedicalType $medicalType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        $medicalType->update($validated);

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/medical-types")->with('success', 'Medical Type updated successfully!');
    }

    public function toggleMedicalType(MedicalType $medicalType)
    {
        $medicalType->status = ($medicalType->status === 'Active') ? 'Inactive' : 'Active';
        $medicalType->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => $medicalType->status]);
        }

        return back()->with('success', 'Status toggled successfully!');
    }

    public function destroyMedicalType(MedicalType $medicalType)
    {
        $medicalType->delete();

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/medical-types")->with('success', 'Medical Type deleted successfully!');
    }

    /**
     * 2. HOSPITAL TYPES
     */
    public function hospitalTypes(Request $request)
    {
        $search = $request->query('search');
        $query = HospitalType::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('id', 'asc')->get();

        return view('master.hospital_types', compact('items', 'search'));
    }

    public function storeHospitalType(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        if (empty($validated['code'])) {
            $maxId = HospitalType::max('id') ?? 0;
            $validated['code'] = 'HT-'.str_pad($maxId + 1, 2, '0', STR_PAD_LEFT);
        }

        HospitalType::create($validated);

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/hospital-types")->with('success', 'Hospital Type added successfully!');
    }

    public function updateHospitalType(Request $request, HospitalType $hospitalType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        $hospitalType->update($validated);

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/hospital-types")->with('success', 'Hospital Type updated successfully!');
    }

    public function toggleHospitalType(HospitalType $hospitalType)
    {
        $hospitalType->status = ($hospitalType->status === 'Active') ? 'Inactive' : 'Active';
        $hospitalType->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => $hospitalType->status]);
        }

        return back()->with('success', 'Status toggled successfully!');
    }

    public function destroyHospitalType(HospitalType $hospitalType)
    {
        $hospitalType->delete();

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/hospital-types")->with('success', 'Hospital Type deleted successfully!');
    }

    /**
     * 3. LABORATORY TYPES
     */
    public function laboratoryTypes(Request $request)
    {
        $search = $request->query('search');
        $query = LaboratoryType::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('id', 'asc')->get();

        return view('master.laboratory_types', compact('items', 'search'));
    }

    public function storeLaboratoryType(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        if (empty($validated['code'])) {
            $maxId = LaboratoryType::max('id') ?? 0;
            $validated['code'] = 'LT-'.str_pad($maxId + 1, 2, '0', STR_PAD_LEFT);
        }

        LaboratoryType::create($validated);

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/laboratory-types")->with('success', 'Laboratory Type added successfully!');
    }

    public function updateLaboratoryType(Request $request, LaboratoryType $laboratoryType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        $laboratoryType->update($validated);

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/laboratory-types")->with('success', 'Laboratory Type updated successfully!');
    }

    public function toggleLaboratoryType(LaboratoryType $laboratoryType)
    {
        $laboratoryType->status = ($laboratoryType->status === 'Active') ? 'Inactive' : 'Active';
        $laboratoryType->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => $laboratoryType->status]);
        }

        return back()->with('success', 'Status toggled successfully!');
    }

    public function destroyLaboratoryType(LaboratoryType $laboratoryType)
    {
        $laboratoryType->delete();

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/laboratory-types")->with('success', 'Laboratory Type deleted successfully!');
    }

    /**
     * 4. PHARMACY TYPES
     */
    public function pharmacyTypes(Request $request)
    {
        $search = $request->query('search');
        $query = PharmacyType::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('id', 'asc')->get();

        return view('master.pharmacy_types', compact('items', 'search'));
    }

    public function storePharmacyType(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        if (empty($validated['code'])) {
            $maxId = PharmacyType::max('id') ?? 0;
            $validated['code'] = 'PT-'.str_pad($maxId + 1, 2, '0', STR_PAD_LEFT);
        }

        PharmacyType::create($validated);

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/pharmacy-types")->with('success', 'Pharmacy Type added successfully!');
    }

    public function updatePharmacyType(Request $request, PharmacyType $pharmacyType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ]);

        $pharmacyType->update($validated);

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/pharmacy-types")->with('success', 'Pharmacy Type updated successfully!');
    }

    public function togglePharmacyType(PharmacyType $pharmacyType)
    {
        $pharmacyType->status = ($pharmacyType->status === 'Active') ? 'Inactive' : 'Active';
        $pharmacyType->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => $pharmacyType->status]);
        }

        return back()->with('success', 'Status toggled successfully!');
    }

    public function destroyPharmacyType(PharmacyType $pharmacyType)
    {
        $pharmacyType->delete();

        $role = auth()->user()?->role_slug ?? 'superadmin';

        return redirect("/{$role}/master/pharmacy-types")->with('success', 'Pharmacy Type deleted successfully!');
    }
}
