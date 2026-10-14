<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['id', 'name', 'email', 'password', 'role', 'role_id', 'hospital_name', 'hospital_id', 'status', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * Normalize role variations: 'Lab admin', 'Lab Admin', 'Laboratory  Admin', 'Laboratory admin' -> 'Laboratory Admin'
     */
    public function getRoleAttribute($value): ?string
    {
        if (self::isLabAdminRole($value)) {
            return 'Laboratory Admin';
        }

        return $value;
    }

    public function setRoleAttribute($value): void
    {
        if (self::isLabAdminRole($value)) {
            $this->attributes['role'] = 'Laboratory Admin';

            return;
        }

        if (! empty($value) && str_contains(strtolower($value), 'super')) {
            $this->attributes['role'] = 'Super Admin';

            return;
        }

        $this->attributes['role'] = $value;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Associated Hospital facility (if any)
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Associated custom Role (if assigned)
     */
    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if user has specific module and action permission
     */
    public function hasPermission(string $module, string $action = 'view'): bool
    {
        if ($this->is_admin) {
            return true;
        }

        $role = null;

        // 1. Direct role_id on User
        if (! empty($this->role_id)) {
            $role = Role::find($this->role_id);
        }

        // 2. Linked via BranchManager record
        if (! $role) {
            $manager = BranchManager::where('email', $this->email)->first();
            if ($manager && ! empty($manager->role_id)) {
                $role = Role::find($manager->role_id);
            }
        }

        // 3. Linked via Staff record
        if (! $role) {
            $staff = Staff::where('email', $this->email)->first();
            if ($staff && ! empty($staff->role_id)) {
                $role = Role::find($staff->role_id);
            }
        }

        if ($role && $role->status !== 'Inactive') {
            return $role->hasPermission($module, $action);
        }

        return false;
    }

    /**
     * Check if user has access to a module (for sidebar display)
     */
    public function canAccessModule(string $module): bool
    {
        // Admin-only modules are strictly blocked for non-admin custom roles
        if (! $this->is_admin) {
            if (in_array($module, ['roles', 'permissions', 'admin_permission', 'addadmin', 'master'])) {
                return false;
            }
            if ($this->is_branch_manager && $module === 'branches') {
                return false;
            }
        }

        if ($this->is_admin) {
            return true;
        }

        if ($module === 'dashboard') {
            return true;
        }

        return $this->hasPermission($module, 'view');
    }

    /**
     * Associated Branch Manager record (if any)
     */
    public function branchManager()
    {
        return $this->hasOne(BranchManager::class, 'email', 'email');
    }

    /**
     * Get URL-friendly role slug (e.g. 'superadmin', 'admin', 'laboratory')
     */
    /**
     * Get URL-friendly role slug (e.g. 'superadmin', 'admin', 'laboratory', 'branch-manager', 'branch-admin')
     */
    public function getRoleSlugAttribute(): string
    {
        // 1. If assigned custom role with a slug
        if ($this->relationLoaded('roleModel') && $this->roleModel && ! empty($this->roleModel->slug)) {
            return $this->roleModel->slug;
        }

        if (! empty($this->role_id)) {
            $r = Role::find($this->role_id);
            if ($r && ! empty($r->slug)) {
                return $r->slug;
            }
        }

        $role = trim($this->role ?? '');

        if (empty($role)) {
            return 'superadmin';
        }

        $lowerRole = strtolower($role);

        if (str_contains($lowerRole, 'super')) {
            return 'superadmin';
        }

        if (str_contains($lowerRole, 'branch') && str_contains($lowerRole, 'admin')) {
            return 'branch-admin';
        }

        if (str_contains($lowerRole, 'branch') || str_contains($lowerRole, 'manager')) {
            return 'branch-manager';
        }

        if (str_contains($lowerRole, 'laboratory') || str_contains($lowerRole, 'lab')) {
            return 'laboratory';
        }

        if ($lowerRole === 'admin') {
            return 'admin';
        }

        $cleanSlug = Str::slug($role);

        return ! empty($cleanSlug) ? $cleanSlug : 'admin';
    }

    /**
     * Check if user is a Laboratory Admin (supports 'Lab admin', 'Lab Admin', 'Laboratory  Admin', 'Laboratory Admin', 'Laboratory admin', etc.)
     */
    public function getIsLabAdminAttribute(): bool
    {
        return $this->isLabAdmin();
    }

    public function isLabAdmin(): bool
    {
        $role = strtolower(preg_replace('/\s+/', ' ', trim($this->role ?? '')));

        if (in_array($role, ['lab admin', 'laboratory admin', 'labadmin', 'laboratoryadmin', 'laboratory', 'lab'])) {
            return true;
        }

        if (in_array($this->role_slug, ['laboratory', 'lab-admin', 'laboratory-admin', 'lab'])) {
            return true;
        }

        return (str_contains($role, 'lab') || str_contains($role, 'laboratory')) && str_contains($role, 'admin') && ! str_contains($role, 'branch');
    }

    /**
     * Check if user is a Super Admin
     */
    public function getIsSuperAdminAttribute(): bool
    {
        return $this->isSuperAdmin();
    }

    public function isSuperAdmin(): bool
    {
        $role = strtolower(preg_replace('/\s+/', ' ', trim($this->role ?? '')));

        return str_contains($role, 'super') || ($this->role_slug ?? '') === 'superadmin';
    }

    /**
     * Check if user has administrative level privileges (Super Admin, Laboratory Admin, Admin)
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        if ($this->is_super_admin || $this->is_lab_admin) {
            return true;
        }

        $role = strtolower(preg_replace('/\s+/', ' ', trim($this->role ?? '')));

        return in_array($role, ['admin', 'super admin', 'laboratory admin', 'lab admin', 'administrator']) || $this->role_slug === 'admin';
    }

    /**
     * Static helper: Check if given role string corresponds to Laboratory Admin
     */
    public static function isLabAdminRole(?string $role): bool
    {
        if (empty($role)) {
            return false;
        }

        $r = strtolower(preg_replace('/\s+/', ' ', trim($role)));

        return in_array($r, ['lab admin', 'laboratory admin', 'labadmin', 'laboratoryadmin', 'laboratory', 'lab'])
            || ((str_contains($r, 'lab') || str_contains($r, 'laboratory')) && str_contains($r, 'admin') && ! str_contains($r, 'branch'));
    }

    /**
     * Static helper: Check if given role string corresponds to Admin privileges
     */
    public static function isAdminRole(?string $role): bool
    {
        if (empty($role)) {
            return false;
        }

        $r = strtolower(preg_replace('/\s+/', ' ', trim($role)));

        return str_contains($r, 'super')
            || self::isLabAdminRole($role)
            || in_array($r, ['admin', 'administrator']);
    }

    /**
     * Determine if user is a Branch Manager
     */
    public function getIsBranchManagerAttribute(): bool
    {
        return str_contains(strtolower($this->role ?? ''), 'manager') ||
               str_contains(strtolower($this->role ?? ''), 'branch') ||
               BranchManager::where('email', $this->email)->exists();
    }

    /**
     * Get display role title for topbar header
     */
    public function getDisplayRoleAttribute(): string
    {
        if ($this->is_branch_manager) {
            return 'Branch Manager';
        }

        if ($this->is_super_admin) {
            return 'Super Admin';
        }

        if ($this->is_lab_admin) {
            return 'Laboratory Admin';
        }

        return $this->role ?? 'Laboratory Admin';
    }

    /**
     * Associated Branch model (if assigned to a branch)
     */
    public function getBranchAttribute(): ?Branch
    {
        if ($this->relationLoaded('branchManager') && $this->branchManager && $this->branchManager->branch) {
            return $this->branchManager->branch;
        }

        $bm = BranchManager::whereRaw('LOWER(email) = ?', [strtolower($this->email)])
            ->orWhere('name', $this->name)
            ->with('branch')
            ->first();

        if ($bm && $bm->branch) {
            return $bm->branch;
        }

        if (! empty($this->branch_id)) {
            return Branch::find($this->branch_id);
        }

        if ($this->is_branch_manager && ! empty($this->hospital_id)) {
            $bm = BranchManager::where('hospital_id', $this->hospital_id)->first();
            if ($bm && $bm->branch) {
                return $bm->branch;
            }

            return Branch::where('hospital_id', $this->hospital_id)->first();
        }

        return null;
    }

    /**
     * Associated Branch name
     */
    public function getBranchNameAttribute(): ?string
    {
        return $this->branch?->name;
    }

    /**
     * Get short form role acronym (e.g. 'BM' for Branch Manager, 'LA' for Lab Admin, 'SA' for Super Admin)
     */
    public function getShortRoleAttribute(): string
    {
        if ($this->is_branch_manager) {
            return 'BM';
        }

        $r = strtolower($this->role ?? '');
        if (str_contains($r, 'super')) {
            return 'SA';
        }
        if (str_contains($r, 'lab')) {
            return 'LA';
        }
        if (str_contains($r, 'front') || str_contains($r, 'reception')) {
            return 'FD';
        }

        return $this->initials ?? 'AD';
    }

    /**
     * Get short form branch code or abbreviated name
     */
    public function getBranchShortAttribute(): ?string
    {
        if ($this->branch) {
            return ! empty($this->branch->branch_code) ? $this->branch->branch_code : $this->branch->name;
        }

        return null;
    }

    /**
     * Get 2-letter avatar initials
     */
    public function getInitialsAttribute(): string
    {
        if ($this->is_branch_manager) {
            return 'BM';
        }

        if (str_contains(strtolower($this->role ?? ''), 'lab')) {
            return 'LA';
        }

        return 'AD';
    }
}
