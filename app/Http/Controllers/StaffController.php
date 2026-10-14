<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchManager;
use App\Models\Hospital;
use App\Models\Role;
use App\Models\Staff;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    protected function getFacilityContext(): array
    {
        $user = Auth::user();
        $isSuperAdmin = ($user->role === 'Super Admin');

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
            $hospital = Hospital::where('type', 'like', '%Lab%')->first() ?? Hospital::first();
        }

        return [$hospital, $isSuperAdmin];
    }

    protected function authorizeStaffPermission(string $action = 'view'): bool
    {
        return Auth::user()->hasPermission('staff', $action);
    }

    public function index(Request $request)
    {
        if (! $this->authorizeStaffPermission('view')) {
            abort(403, 'Unauthorized: You do not have permission to view staff members.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if (! $hospital) {
            abort(403, 'Unauthorized: Facility context missing.');
        }

        $search = $request->query('search');
        $selectedBranchId = $request->query('branch_id');

        // STRICT TENANT ISOLATION: Only branches, categories and roles of THIS laboratory
        $branches = Branch::where('hospital_id', $hospital->id)->where('status', 'Active')->orderBy('name')->get();
        $roles = Role::where('hospital_id', $hospital->id)->where('status', 'Active')->orderBy('name')->get();
        if (Auth::user()->is_branch_manager) {
            $roles = $roles->reject(function ($r) {
                $rLower = strtolower($r->name.' '.$r->slug);

                return str_contains($rLower, 'manager');
            });
        }
        $categories = TestCategory::where('hospital_id', $hospital->id)->where('status', 'Active')->orderBy('name')->get();

        $query = Staff::with(['branch', 'role', 'category']);
        if ($hospital) {
            $query->where('hospital_id', $hospital->id);
        }
        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $selectedBranchId = Auth::user()->branch->id;
            $query->where('branch_id', $selectedBranchId);
        } elseif ($selectedBranchId !== null && $selectedBranchId !== '') {
            $query->where(function ($q) use ($selectedBranchId) {
                $q->where('branch_id', $selectedBranchId)
                    ->orWhereNull('branch_id');
            });
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        $staffMembers = $query->orderBy('name')->get();

        $canCreate = $this->authorizeStaffPermission('create');
        $canEdit = $this->authorizeStaffPermission('edit');
        $canDelete = $this->authorizeStaffPermission('delete');

        return view('laboratory.staff.index', compact(
            'hospital',
            'branches',
            'roles',
            'categories',
            'staffMembers',
            'search',
            'selectedBranchId',
            'canCreate',
            'canEdit',
            'canDelete'
        ));
    }

    public function store(Request $request)
    {
        if (! $this->authorizeStaffPermission('create')) {
            return back()->with('error', 'You do not have permission to add staff members.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if (! $hospital) {
            abort(403, 'Unauthorized: Facility context missing.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'digits:10'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'designation' => ['nullable', 'string', 'max:100'],
            'qualification' => ['nullable', 'string', 'max:100'],
            'branch_id' => ['nullable'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'test_category_id' => ['nullable', 'exists:test_categories,id'],
            'status' => ['required', 'in:Active,Inactive'],
        ], [
            'name.regex' => 'Staff full name must only contain letters, dots, and spaces (numbers are not allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $validated['branch_id'] = Auth::user()->branch->id;
        }

        if (! empty($validated['branch_id'])) {
            Branch::where('id', $validated['branch_id'])->where('hospital_id', $hospital->id)->firstOrFail();
        }
        if (! empty($validated['role_id'])) {
            $roleModel = Role::where('id', $validated['role_id'])->where('hospital_id', $hospital->id)->firstOrFail();
            if (Auth::user()->is_branch_manager && str_contains(strtolower($roleModel->name.' '.$roleModel->slug), 'manager')) {
                return back()->with('error', 'Unauthorized: Branch Managers cannot create or assign another Branch Manager.');
            }
        }
        if (! empty($validated['test_category_id'])) {
            TestCategory::where('id', $validated['test_category_id'])->where('hospital_id', $hospital->id)->firstOrFail();
        }

        // Front desk / Receptionist staff is NOT linked to any test category
        $isFrontDesk = false;
        if (! empty($validated['role_id'])) {
            $roleModel = Role::find($validated['role_id']);
            if ($roleModel) {
                $rLower = strtolower($roleModel->name.' '.$roleModel->slug);
                $isFrontDesk = str_contains($rLower, 'front') || str_contains($rLower, 'reception');
            }
        }
        $testCategoryId = $isFrontDesk ? null : (! empty($validated['test_category_id']) ? $validated['test_category_id'] : null);

        $staff = Staff::create([
            'hospital_id' => $hospital->id,
            'branch_id' => ! empty($validated['branch_id']) ? $validated['branch_id'] : null,
            'role_id' => ! empty($validated['role_id']) ? $validated['role_id'] : null,
            'test_category_id' => $testCategoryId,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'qualification' => $validated['qualification'] ?? null,
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Staff member '{$staff->name}' registered successfully!");
    }

    public function update(Request $request, Staff $staff)
    {
        if (! $this->authorizeStaffPermission('edit')) {
            return back()->with('error', 'You do not have permission to edit staff members.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if ($staff->hospital_id !== $hospital?->id) {
            abort(403, 'Unauthorized to modify staff from another facility.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'digits:10'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'designation' => ['nullable', 'string', 'max:100'],
            'qualification' => ['nullable', 'string', 'max:100'],
            'branch_id' => ['nullable'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'test_category_id' => ['nullable', 'exists:test_categories,id'],
            'status' => ['required', 'in:Active,Inactive'],
        ], [
            'name.regex' => 'Staff full name must only contain letters, dots, and spaces (numbers are not allowed).',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
        ]);

        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $validated['branch_id'] = Auth::user()->branch->id;
        }

        if (! empty($validated['branch_id'])) {
            Branch::where('id', $validated['branch_id'])->where('hospital_id', $hospital->id)->firstOrFail();
        }
        if (! empty($validated['role_id'])) {
            $roleModel = Role::where('id', $validated['role_id'])->where('hospital_id', $hospital->id)->firstOrFail();
            if (Auth::user()->is_branch_manager && str_contains(strtolower($roleModel->name.' '.$roleModel->slug), 'manager')) {
                return back()->with('error', 'Unauthorized: Branch Managers cannot create or assign another Branch Manager.');
            }
        }
        if (! empty($validated['test_category_id'])) {
            TestCategory::where('id', $validated['test_category_id'])->where('hospital_id', $hospital->id)->firstOrFail();
        }

        // Front desk / Receptionist staff is NOT linked to any test category
        $isFrontDesk = false;
        if (! empty($validated['role_id'])) {
            $roleModel = Role::find($validated['role_id']);
            if ($roleModel) {
                $rLower = strtolower($roleModel->name.' '.$roleModel->slug);
                $isFrontDesk = str_contains($rLower, 'front') || str_contains($rLower, 'reception');
            }
        }
        $testCategoryId = $isFrontDesk ? null : (! empty($validated['test_category_id']) ? $validated['test_category_id'] : null);

        $staff->update([
            'branch_id' => ! empty($validated['branch_id']) ? $validated['branch_id'] : null,
            'role_id' => ! empty($validated['role_id']) ? $validated['role_id'] : null,
            'test_category_id' => $testCategoryId,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'qualification' => $validated['qualification'] ?? null,
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Staff member '{$staff->name}' updated successfully!");
    }

    public function destroy(Staff $staff)
    {
        if (! $this->authorizeStaffPermission('delete')) {
            return back()->with('error', 'You do not have permission to delete staff members.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if ($staff->hospital_id !== $hospital?->id) {
            abort(403, 'Unauthorized to delete staff belonging to another facility.');
        }

        $name = $staff->name;
        $staff->delete();

        return back()->with('success', "Staff member '{$name}' deleted successfully.");
    }
}
