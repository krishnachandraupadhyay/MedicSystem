<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchManager;
use App\Models\Hospital;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BranchController extends Controller
{
    /**
     * Resolve currently active hospital and check multi-branch permission
     */
    protected function getFacilityContext()
    {
        $user = Auth::user();
        $isSuperAdmin = ($user->role_slug ?? 'superadmin') === 'superadmin';

        $hospital = null;
        if (! empty($user->hospital_id)) {
            $hospital = Hospital::find($user->hospital_id);
        }
        if (! $hospital && $user->hospital) {
            $hospital = $user->hospital;
        }
        if (! $hospital) {
            $hospital = Hospital::where('email', $user->email)->first()
                ?? Hospital::where('name', $user->hospital_name)->first();
        }
        if (! $hospital) {
            $manager = BranchManager::where('email', $user->email)->first();
            if ($manager) {
                $hospital = $manager->hospital ?? ($manager->branch ? $manager->branch->hospital : null);
            }
        }
        if (! $hospital && $isSuperAdmin) {
            $hospital = Hospital::first();
        }

        // If super admin, permission is granted or depends on selected hospital
        $isMultiBranchEnabled = $isSuperAdmin ? true : ($hospital ? (bool) $hospital->enable_multi_branch : false);

        return [$hospital, $isMultiBranchEnabled, $isSuperAdmin];
    }

    /**
     * Strictly authorize manager management (Lab Admin & Super Admin only)
     */
    protected function authorizeManagerManagement(): void
    {
        $user = Auth::user();
        if (! in_array($user->role, ['Super Admin', 'Laboratory Admin', 'Admin'])) {
            abort(403, 'Unauthorized: Only Laboratory Admin and Super Admin can manage branch managers.');
        }
    }

    /**
     * List & manage Branches ("apna branch jod ke")
     */
    public function branches(Request $request)
    {
        if (! in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin'])) {
            if (Auth::user()->is_branch_manager || ! Auth::user()->hasPermission('branches', 'view')) {
                abort(403, 'Unauthorized: You do not have permission to view branches.');
            }
        }

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        $search = $request->query('search');
        $query = Branch::with(['hospital', 'managers']);

        // Strict isolation: only branches added for this facility (Super Admin views all)
        if (! $isSuperAdmin && $hospital) {
            $query->where('hospital_id', $hospital->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('branch_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $branches = $query->orderBy('id', 'asc')->get();
        $hospitals = Hospital::where('status', 'Active')->get();

        return view('branches.index', compact('branches', 'hospital', 'hospitals', 'isMultiBranchEnabled', 'isSuperAdmin', 'search'));
    }

    /**
     * Store a new Branch
     */
    public function storeBranch(Request $request)
    {
        if (! in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin'])) {
            if (Auth::user()->is_branch_manager || ! Auth::user()->hasPermission('branches', 'create')) {
                abort(403, 'Unauthorized: You do not have permission to create branches.');
            }
        }

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        $validated = $request->validate([
            'hospital_id' => ['nullable', 'exists:hospitals,id'],
            'name' => ['required', 'string', 'max:255'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'digits:10'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ], [
            'city.regex' => 'City name must contain only letters and spaces (no numbers allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        if (! $isSuperAdmin && $hospital) {
            $targetHospitalId = $hospital->id;
        } else {
            $targetHospitalId = $validated['hospital_id'] ?? ($hospital ? $hospital->id : Hospital::first()?->id);
        }

        $targetHospital = Hospital::find($targetHospitalId);

        // Enforce multi-branch permission check
        if ($targetHospital && ! $targetHospital->enable_multi_branch && ! $isSuperAdmin) {
            return back()->with('error', 'Multi-Branch feature is currently disabled for this facility. Please enable it in Admin Permissions first.');
        }

        if (empty($validated['branch_code'])) {
            $count = Branch::where('hospital_id', $targetHospitalId)->count();
            $prefix = ($targetHospital && (strtolower($targetHospital->medical_type ?? '') === 'laboratory' || str_contains(strtolower($targetHospital->role ?? ''), 'lab'))) ? 'LAB-BR-' : 'BR-';
            $validated['branch_code'] = $prefix.str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        }

        $validated['hospital_id'] = $targetHospitalId;

        Branch::create($validated);

        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/branches")->with('success', 'Branch created successfully!');
    }

    /**
     * Update existing Branch
     */
    public function updateBranch(Request $request, Branch $branch)
    {
        if (! in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin'])) {
            if (Auth::user()->is_branch_manager || ! Auth::user()->hasPermission('branches', 'edit')) {
                abort(403, 'Unauthorized: You do not have permission to update branches.');
            }
        }

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $branch->hospital_id !== $hospital->id) {
            abort(403, 'Unauthorized to modify a branch belonging to another facility.');
        }

        $validated = $request->validate([
            'hospital_id' => ['nullable', 'exists:hospitals,id'],
            'name' => ['required', 'string', 'max:255'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'digits:10'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s\.\'-]+$/u'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'string'],
        ], [
            'city.regex' => 'City name must contain only letters and spaces (no numbers allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        if (! $isSuperAdmin && $hospital) {
            $validated['hospital_id'] = $hospital->id;
        }

        $branch->update($validated);

        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/branches")->with('success', 'Branch updated successfully!');
    }

    /**
     * Delete Branch
     */
    public function destroyBranch(Branch $branch)
    {
        if (! in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin'])) {
            if (Auth::user()->is_branch_manager || ! Auth::user()->hasPermission('branches', 'delete')) {
                abort(403, 'Unauthorized: You do not have permission to delete branches.');
            }
        }

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $branch->hospital_id !== $hospital->id) {
            abort(403, 'Unauthorized to delete a branch belonging to another facility.');
        }

        $branch->delete();
        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/branches")->with('success', 'Branch deleted successfully!');
    }

    /**
     * Toggle Branch Status
     */
    public function toggleBranch(Branch $branch)
    {
        if (! in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin'])) {
            if (Auth::user()->is_branch_manager || ! Auth::user()->hasPermission('branches', 'edit')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized: You do not have permission to edit branches.'], 403);
            }
        }

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $branch->hospital_id !== $hospital->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $branch->status = ($branch->status === 'Active') ? 'Inactive' : 'Active';
        $branch->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => $branch->status]);
        }

        return back()->with('success', 'Branch status updated!');
    }

    /**
     * List & assign Branch Managers ("branch manager assign kar dega")
     */
    public function managers(Request $request)
    {
        $this->authorizeManagerManagement();

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        $search = $request->query('search');
        $query = BranchManager::with(['branch', 'hospital']);

        if (! $isSuperAdmin && $hospital) {
            $query->where('hospital_id', $hospital->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $managers = $query->orderBy('id', 'asc')->get();

        $branchQuery = Branch::where('status', 'Active');
        if (! $isSuperAdmin && $hospital) {
            $branchQuery->where('hospital_id', $hospital->id);
        }
        $availableBranches = $branchQuery->get();
        $hospitals = Hospital::where('status', 'Active')->get();

        $rolesQuery = Role::where('status', 'Active');
        if (! $isSuperAdmin && $hospital) {
            $rolesQuery->where('hospital_id', $hospital->id);
        }
        $availableRoles = $rolesQuery->get();

        return view('branches.managers', compact('managers', 'availableBranches', 'availableRoles', 'hospital', 'hospitals', 'isMultiBranchEnabled', 'isSuperAdmin', 'search'));
    }

    /**
     * Store & assign a Branch Manager to a Branch
     */
    public function storeManager(Request $request)
    {
        $this->authorizeManagerManagement();

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        $validated = $request->validate([
            'hospital_id' => ['nullable', 'exists:hospitals,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'phone' => ['nullable', 'digits:10'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string'],
        ], [
            'name.regex' => 'Manager full name must only contain letters, dots, and spaces (numbers are not allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        if (! empty($validated['role_id'])) {
            $role = Role::find($validated['role_id']);
            $validated['role_name'] = $role?->name;
        }

        $branch = Branch::find($validated['branch_id']);

        if (! $isSuperAdmin && $hospital) {
            $targetHospitalId = $hospital->id;
            if ($branch && $branch->hospital_id !== $hospital->id) {
                return back()->with('error', 'Selected branch does not belong to your facility.');
            }
        } else {
            $targetHospitalId = $validated['hospital_id'] ?? ($branch ? $branch->hospital_id : ($hospital ? $hospital->id : null));
        }

        $targetHospital = Hospital::find($targetHospitalId);

        // Enforce multi-branch permission check
        if ($targetHospital && ! $targetHospital->enable_multi_branch && ! $isSuperAdmin) {
            return back()->with('error', 'Multi-Branch feature is currently disabled for this facility. Please enable it in Admin Permissions first.');
        }

        if (empty($validated['employee_id'])) {
            $count = BranchManager::where('hospital_id', $targetHospitalId)->count();
            $prefix = ($targetHospital && (strtolower($targetHospital->medical_type ?? '') === 'laboratory' || str_contains(strtolower($targetHospital->role ?? ''), 'lab'))) ? 'LAB-BM-' : 'BM-';
            $validated['employee_id'] = $prefix.str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        }

        $validated['hospital_id'] = $targetHospitalId;

        // Create BranchManager record
        $managerData = collect($validated)->except(['password'])->toArray();
        BranchManager::create($managerData);

        // Create or update login User account
        $rawPassword = $request->input('password') ?: '123456';
        $user = User::where('email', $validated['email'])->first();
        if ($user) {
            $userData = [
                'name' => $validated['name'],
                'role' => $validated['role_name'] ?? 'Branch Manager',
                'role_id' => $validated['role_id'] ?? null,
                'hospital_id' => $targetHospitalId,
                'hospital_name' => $targetHospital?->name ?? $user->hospital_name,
                'phone' => $validated['phone'] ?? $user->phone,
                'status' => $validated['status'],
            ];
            if (! empty($request->input('password'))) {
                $userData['password'] = Hash::make($request->input('password'));
            }
            $user->update($userData);
        } else {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($rawPassword),
                'role' => $validated['role_name'] ?? 'Branch Manager',
                'role_id' => $validated['role_id'] ?? null,
                'hospital_id' => $targetHospitalId,
                'hospital_name' => $targetHospital?->name ?? 'Hospital Facility',
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'],
            ]);
        }

        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/branch-managers")->with('success', 'Branch Manager assigned successfully with login access!');
    }

    /**
     * Update Branch Manager
     */
    public function updateManager(Request $request, BranchManager $branchManager)
    {
        $this->authorizeManagerManagement();

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $branchManager->hospital_id !== $hospital->id) {
            abort(403, 'Unauthorized to modify a manager belonging to another facility.');
        }

        $validated = $request->validate([
            'hospital_id' => ['nullable', 'exists:hospitals,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'phone' => ['nullable', 'digits:10'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string'],
        ], [
            'name.regex' => 'Manager full name must only contain letters, dots, and spaces (numbers are not allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        if (! empty($validated['role_id'])) {
            $role = Role::find($validated['role_id']);
            $validated['role_name'] = $role?->name;
        } else {
            $validated['role_name'] = null;
        }

        $branch = Branch::find($validated['branch_id']);
        if (! $isSuperAdmin && $hospital) {
            $validated['hospital_id'] = $hospital->id;
            if ($branch && $branch->hospital_id !== $hospital->id) {
                return back()->with('error', 'Selected branch does not belong to your facility.');
            }
        }

        $oldEmail = $branchManager->email;
        $managerData = collect($validated)->except(['password'])->toArray();
        $branchManager->update($managerData);

        // Sync linked User account
        $user = User::where('email', $oldEmail)->first() ?? User::where('email', $validated['email'])->first();
        if ($user) {
            $userUpdates = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role_name'] ?? 'Branch Manager',
                'role_id' => $validated['role_id'] ?? null,
                'hospital_id' => $validated['hospital_id'] ?? $branchManager->hospital_id,
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'],
            ];
            if (! empty($validated['password'])) {
                $userUpdates['password'] = Hash::make($validated['password']);
            }
            $user->update($userUpdates);
        } elseif (! empty($validated['email'])) {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? '123456'),
                'role' => $validated['role_name'] ?? 'Branch Manager',
                'role_id' => $validated['role_id'] ?? null,
                'hospital_id' => $validated['hospital_id'] ?? $branchManager->hospital_id,
                'hospital_name' => $branchManager->hospital?->name ?? 'Hospital Facility',
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'],
            ]);
        }

        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/branch-managers")->with('success', 'Branch Manager updated successfully!');
    }

    /**
     * Delete Branch Manager
     */
    public function destroyManager(BranchManager $branchManager)
    {
        $this->authorizeManagerManagement();

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $branchManager->hospital_id !== $hospital->id) {
            abort(403, 'Unauthorized to delete a manager belonging to another facility.');
        }

        $managerEmail = $branchManager->email;
        $branchManager->delete();

        // If a linked user exists with role Branch Manager, delete or deactivate
        if ($managerEmail) {
            $user = User::where('email', $managerEmail)->first();
            if ($user && in_array($user->role, ['Branch Manager', $branchManager->role_name])) {
                $user->delete();
            }
        }

        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/branch-managers")->with('success', 'Branch Manager deleted successfully!');
    }

    /**
     * Toggle Branch Manager Status
     */
    public function toggleManager(BranchManager $branchManager)
    {
        $this->authorizeManagerManagement();

        [$hospital, $isMultiBranchEnabled, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $branchManager->hospital_id !== $hospital->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $branchManager->status = ($branchManager->status === 'Active') ? 'Inactive' : 'Active';
        $branchManager->save();

        if ($branchManager->email) {
            $user = User::where('email', $branchManager->email)->first();
            if ($user) {
                $user->status = $branchManager->status;
                $user->save();
            }
        }

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => $branchManager->status]);
        }

        return back()->with('success', 'Manager status updated!');
    }
}
