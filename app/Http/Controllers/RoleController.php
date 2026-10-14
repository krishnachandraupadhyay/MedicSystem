<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Standard module definitions with their granular actions
     */
    public static function getPermissionModules(): array
    {
        return [
            'patients' => [
                'label' => 'Patient Management',
                'description' => 'Permissions for patient intake, registration, and medical records',
                'icon' => 'fa-solid fa-hospital-user',
                'badge_color' => '#ec4899',
                'actions' => [
                    'view' => 'View Patients',
                    'create' => 'Create Patient',
                    'edit' => 'Edit Patient',
                    'delete' => 'Delete Patient',
                ],
            ],
            'staff' => [
                'label' => 'Staff Management',
                'description' => 'Permissions for hospital & lab staff members',
                'icon' => 'fa-solid fa-users',
                'badge_color' => '#6366f1',
                'actions' => [
                    'view' => 'View Staff',
                    'create' => 'Create Staff',
                    'edit' => 'Edit Staff',
                    'delete' => 'Delete Staff',
                    'attendance' => 'Staff Attendance',
                ],
            ],
            'attendance' => [
                'label' => 'Attendance Management',
                'description' => 'Permissions for staff check-in, tracking, and logs',
                'icon' => 'fa-solid fa-calendar-check',
                'badge_color' => '#10b981',
                'actions' => [
                    'view' => 'View Attendance',
                    'create' => 'Create / Mark Attendance',
                ],
            ],
            'branches' => [
                'label' => 'Branch Management',
                'description' => 'Permissions for managing branches & collection centers',
                'icon' => 'fa-solid fa-building',
                'badge_color' => '#f59e0b',
                'actions' => [
                    'view' => 'View Branches',
                    'create' => 'Create Branch',
                    'edit' => 'Edit Branch',
                    'delete' => 'Delete Branch',
                ],
            ],
            'tests' => [
                'label' => 'Test Management',
                'description' => 'Permissions for creating, editing, viewing, and managing tests, test categories & pricing',
                'icon' => 'fa-solid fa-flask-vial',
                'badge_color' => '#06b6d4',
                'actions' => [
                    'view' => 'View Tests',
                    'create' => 'Create Test',
                    'edit' => 'Edit Test',
                    'delete' => 'Delete Test',
                ],
            ],
            'reports' => [
                'label' => 'Diagnostic Reports',
                'description' => 'Permissions for generating and modifying medical lab reports',
                'icon' => 'fa-solid fa-file-waveform',
                'badge_color' => '#8b5cf6',
                'actions' => [
                    'view' => 'View Reports',
                    'create' => 'Generate Report',
                    'edit' => 'Edit Report',
                    'delete' => 'Delete Report',
                ],
            ],
        ];
    }

    /**
     * Resolve currently active hospital context
     */
    protected function getFacilityContext(): array
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
        if (! $hospital && $isSuperAdmin) {
            $hospital = Hospital::where('type', 'like', '%Lab%')->first() ?? Hospital::first();
        }

        return [$hospital, $isSuperAdmin];
    }

    /**
     * Safely resolve Role from injected model, route parameter, or ID
     */
    protected function resolveRole($role = null): Role
    {
        if ($role instanceof Role && $role->exists) {
            return $role;
        }

        $id = null;
        if ($role instanceof Role) {
            $id = $role->id;
        } elseif (is_numeric($role) || is_string($role)) {
            $id = $role;
        }

        if (! $id) {
            $id = request()->route('role')
                ?? request()->route('roleModel')
                ?? request()->route('id')
                ?? request()->input('role_id');
        }

        if ($id instanceof Role && $id->exists) {
            return $id;
        }

        if (is_object($id) && isset($id->id)) {
            $id = $id->id;
        }

        return Role::findOrFail((int) $id);
    }

    /**
     * Strictly authorize role and permission management (Lab Admin & Super Admin only)
     */
    protected function authorizeRoleManagement(): void
    {
        $user = Auth::user();
        if (! $user) {
            abort(401);
        }

        $allowedRoles = ['Super Admin', 'Laboratory Admin', 'Admin'];
        $isAllowed = in_array($user->role, $allowedRoles)
            || in_array($user->role_slug, ['superadmin', 'admin', 'laboratory']);

        if (! $isAllowed) {
            abort(403, 'Unauthorized: Only Laboratory Admin can manage facility roles and permissions.');
        }
    }

    /**
     * List all created roles
     */
    public function index(Request $request)
    {
        $this->authorizeRoleManagement();

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        $search = $request->query('search');
        $query = Role::with('hospital');

        if ($hospital) {
            $query->where('hospital_id', $hospital->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $roles = $query->orderBy('id', 'desc')->get();
        $modules = self::getPermissionModules();
        $hospitals = Hospital::where('status', 'Active')->get();

        return view('laboratory.roles.index', compact('roles', 'modules', 'hospital', 'hospitals', 'isSuperAdmin', 'search'));
    }

    /**
     * Store newly created Role with granular permissions
     */
    public function store(Request $request)
    {
        $this->authorizeRoleManagement();

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        $validated = $request->validate([
            'hospital_id' => ['nullable', 'exists:hospitals,id'],
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\(\)\/\'_-]+$/u'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
        ], [
            'name.regex' => 'Role name must only contain letters, brackets, and spaces (numbers are not allowed).',
        ]);

        $status = $validated['status'] ?? 'Active';

        if (! $isSuperAdmin && $hospital) {
            $targetHospitalId = $hospital->id;
        } else {
            $targetHospitalId = $validated['hospital_id'] ?? ($hospital ? $hospital->id : null);
        }

        $modules = self::getPermissionModules();
        $rawPerms = $request->input('permissions', []);
        $formattedPermissions = [];

        // Build standard permission schema (defaulting unselected toggles to false/off)
        foreach ($modules as $modKey => $modData) {
            $formattedPermissions[$modKey] = [];
            foreach ($modData['actions'] as $actKey => $actLabel) {
                $formattedPermissions[$modKey][$actKey] = ! empty($rawPerms[$modKey][$actKey]);
            }
        }

        $role = Role::create([
            'hospital_id' => $targetHospitalId,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'permissions' => $formattedPermissions,
            'status' => $status,
        ]);

        $roleSlug = Auth::user()->role_slug ?? 'laboratory';

        return redirect("/{$roleSlug}/roles")->with('success', "Role '{$role->name}' created successfully with assigned permissions!");
    }

    /**
     * Update an existing Role and its permissions
     */
    public function update(Request $request, $role = null)
    {
        $this->authorizeRoleManagement();

        $role = $this->resolveRole($role);

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $role->hospital_id && (int) $role->hospital_id !== (int) $hospital->id) {
            abort(403, 'Unauthorized to modify role belonging to another facility.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\(\)\/\'_-]+$/u'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'permissions' => ['nullable', 'array'],
        ], [
            'name.regex' => 'Role name must only contain letters, brackets, and spaces (numbers are not allowed).',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ];

        if ($request->has('permissions')) {
            $modules = self::getPermissionModules();
            $rawPerms = $request->input('permissions', []);
            $formattedPermissions = [];

            foreach ($modules as $modKey => $modData) {
                $formattedPermissions[$modKey] = [];
                foreach ($modData['actions'] as $actKey => $actLabel) {
                    $formattedPermissions[$modKey][$actKey] = ! empty($rawPerms[$modKey][$actKey]);
                }
            }
            $updateData['permissions'] = $formattedPermissions;
        }

        $role->update($updateData);

        $roleSlug = Auth::user()->role_slug ?? 'laboratory';

        return redirect("/{$roleSlug}/roles")->with('success', "Role '{$role->name}' updated successfully!");
    }

    /**
     * Dedicated Page 2: Role Permissions Assignment
     */
    public function permissionsIndex(Request $request)
    {
        $this->authorizeRoleManagement();

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        $query = Role::with('hospital');
        if ($hospital) {
            $query->where(function ($q) use ($hospital) {
                $q->where('hospital_id', $hospital->id)
                    ->orWhereNull('hospital_id');
            });
        }
        $roles = $query->orderBy('name')->get();

        $selectedRoleId = $request->query('role_id');
        $selectedRole = null;

        if ($selectedRoleId) {
            $selectedRole = $roles->firstWhere('id', (int) $selectedRoleId);
        }
        if (! $selectedRole && $roles->isNotEmpty()) {
            $selectedRole = $roles->first();
        }

        $modules = self::getPermissionModules();

        return view('laboratory.roles.permissions', compact(
            'roles',
            'selectedRole',
            'modules',
            'hospital',
            'isSuperAdmin'
        ));
    }

    /**
     * Update permissions for a specific role
     */
    public function updatePermissions(Request $request, $role = null)
    {
        $this->authorizeRoleManagement();

        $role = $this->resolveRole($role);

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $role->hospital_id && (int) $role->hospital_id !== (int) $hospital->id) {
            abort(403, 'Unauthorized to modify permissions for role belonging to another facility.');
        }

        if (! $role->hospital_id && $hospital) {
            $role->hospital_id = $hospital->id;
        }

        $modules = self::getPermissionModules();
        $rawPerms = $request->input('permissions', []);
        $formattedPermissions = [];

        foreach ($modules as $modKey => $modData) {
            $formattedPermissions[$modKey] = [];
            foreach ($modData['actions'] as $actKey => $actLabel) {
                $formattedPermissions[$modKey][$actKey] = ! empty($rawPerms[$modKey][$actKey]);
            }
        }

        $role->update([
            'hospital_id' => $role->hospital_id,
            'permissions' => $formattedPermissions,
        ]);

        $roleSlug = Auth::user()->role_slug ?? 'laboratory';

        return redirect("/{$roleSlug}/permissions?role_id={$role->id}")
            ->with('success', "Permissions updated successfully for Role '{$role->name}'!");
    }

    /**
     * Toggle Role Status
     */
    public function toggle($role = null)
    {
        $this->authorizeRoleManagement();

        $role = $this->resolveRole($role);

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $role->hospital_id && (int) $role->hospital_id !== (int) $hospital->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $role->status = ($role->status === 'Active') ? 'Inactive' : 'Active';
        $role->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => $role->status]);
        }

        return back()->with('success', "Role '{$role->name}' status updated to {$role->status}!");
    }

    /**
     * Delete Role
     */
    public function destroy($role = null)
    {
        $this->authorizeRoleManagement();

        $role = $this->resolveRole($role);

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        if (! $isSuperAdmin && $hospital && $role->hospital_id && (int) $role->hospital_id !== (int) $hospital->id) {
            abort(403, 'Unauthorized to delete role belonging to another facility.');
        }

        $name = $role->name;
        $role->delete();

        $roleSlug = Auth::user()->role_slug ?? 'laboratory';

        return redirect("/{$roleSlug}/roles")->with('success', "Role '{$name}' deleted successfully!");
    }
}
