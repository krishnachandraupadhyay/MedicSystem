<?php

use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Default generic redirects (redirects to the logged in user's role panel)
    Route::get('/dashboard', function () {
        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/dashboard");
    })->name('dashboard');

    Route::get('/admin', function () {
        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/addadmin");
    })->name('admin.hospitals');

    Route::get('/addadmin', function () {
        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/addadmin");
    });

    Route::get('/admin-permission', function () {
        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/admin-permission");
    })->name('admin.permission');

    Route::get('/branches', function () {
        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/branches");
    })->name('branches');

    Route::get('/branch-managers', function () {
        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/branch-managers");
    })->name('branch_managers');

    Route::get('/roles', function () {
        $role = Auth::user()->role_slug ?? 'superadmin';

        return redirect("/{$role}/roles");
    })->name('roles');

    Route::get('/tests', function () {
        $role = Auth::user()->role_slug ?? 'laboratory';
        $qs = request()->getQueryString();

        return redirect("/{$role}/tests".($qs ? "?{$qs}" : ''));
    })->name('tests');

    Route::get('/test-categories', function () {
        $role = Auth::user()->role_slug ?? 'laboratory';
        $qs = request()->getQueryString();

        return redirect("/{$role}/test-categories".($qs ? "?{$qs}" : ''));
    })->name('test_categories');

    Route::get('/test-fees', function () {
        $role = Auth::user()->role_slug ?? 'laboratory';
        $qs = request()->getQueryString();

        return redirect("/{$role}/test-fees".($qs ? "?{$qs}" : ''));
    })->name('test_fees');

    Route::get('/staff', function () {
        $role = Auth::user()->role_slug ?? 'laboratory';
        $qs = request()->getQueryString();

        return redirect("/{$role}/staff".($qs ? "?{$qs}" : ''));
    })->name('staff');

    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/medical-types', function () {
            $role = Auth::user()->role_slug ?? 'superadmin';

            return redirect("/{$role}/master/medical-types");
        })->name('medical_types');

        Route::get('/hospital-types', function () {
            $role = Auth::user()->role_slug ?? 'superadmin';

            return redirect("/{$role}/master/hospital-types");
        })->name('hospital_types');

        Route::get('/laboratory-types', function () {
            $role = Auth::user()->role_slug ?? 'superadmin';

            return redirect("/{$role}/master/laboratory-types");
        })->name('laboratory_types');

        Route::get('/pharmacy-types', function () {
            $role = Auth::user()->role_slug ?? 'superadmin';

            return redirect("/{$role}/master/pharmacy-types");
        })->name('pharmacy_types');
    });

    // Dynamic Role-Prefixed Routes (e.g. /superadmin/..., /admin/..., /laboratory/..., /branch-manager/..., /branch-admin/...)
    $roles = [
        'superadmin',
        'admin',
        'laboratory',
        'branch-manager',
        'branchmanager',
        'branch-admin',
        'branchadmin',
    ];
    try {
        if (Schema::hasTable('roles')) {
            $dbRoles = Role::pluck('slug')->filter()->all();
            $roles = array_unique(array_merge($roles, $dbRoles));
        }
    } catch (Throwable $e) {
        // Fallback to static list
    }

    foreach ($roles as $role) {
        Route::prefix($role)->group(function () use ($role) {
            // Dashboard: /{role}/dashboard
            Route::get('/dashboard', function () use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'superadmin';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        return redirect("/{$userRole}/dashboard");
                    }
                }

                $user = Auth::user();
                if ($user && $user->role === 'Super Admin' && in_array($role, ['superadmin', 'admin'])) {
                    return app(DashboardController::class)->index(request());
                }

                return app(LaboratoryController::class)->index(request());
            })->name("{$role}.dashboard");

            // Add Admin / Admin Management: /{role}/addadmin
            Route::get('/addadmin', [DashboardController::class, 'hospitals'])->name("{$role}.addadmin");
            Route::post('/addadmin', [DashboardController::class, 'storeHospital'])->name("{$role}.hospitals.store");
            Route::put('/addadmin/{hospital}', [DashboardController::class, 'updateHospital'])->name("{$role}.hospitals.update");
            Route::post('/addadmin/{hospital}/toggle', [DashboardController::class, 'toggleHospitalStatus'])->name("{$role}.hospitals.toggle");
            Route::delete('/addadmin/{hospital}', [DashboardController::class, 'destroyHospital'])->name("{$role}.hospitals.destroy");

            // Admin Permissions: /{role}/admin-permission
            Route::get('/admin-permission', [AdminPermissionController::class, 'index'])->name("{$role}.admin_permission");
            Route::put('/admin-permission/{hospital}', [AdminPermissionController::class, 'update'])->name("{$role}.admin_permission.update");
            Route::post('/admin-permission/{hospital}/toggle', [AdminPermissionController::class, 'toggle'])->name("{$role}.admin_permission.toggle");

            // Branches: /{role}/branches
            Route::get('/branches', function (Request $request) use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'superadmin';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        $qs = $request->getQueryString();

                        return redirect("/{$userRole}/branches".($qs ? "?{$qs}" : ''));
                    }
                }

                return app(BranchController::class)->branches($request);
            })->name("{$role}.branches");
            Route::post('/branches', [BranchController::class, 'storeBranch'])->name("{$role}.branches.store");
            Route::put('/branches/{branch}', [BranchController::class, 'updateBranch'])->name("{$role}.branches.update");
            Route::post('/branches/{branch}/toggle', [BranchController::class, 'toggleBranch'])->name("{$role}.branches.toggle");
            Route::delete('/branches/{branch}', [BranchController::class, 'destroyBranch'])->name("{$role}.branches.destroy");

            // Branch Managers: /{role}/branch-managers
            Route::get('/branch-managers', function (Request $request) use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'superadmin';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        $qs = $request->getQueryString();

                        return redirect("/{$userRole}/branch-managers".($qs ? "?{$qs}" : ''));
                    }
                }

                return app(BranchController::class)->managers($request);
            })->name("{$role}.branch_managers");
            Route::post('/branch-managers', [BranchController::class, 'storeManager'])->name("{$role}.branch_managers.store");
            Route::put('/branch-managers/{branchManager}', [BranchController::class, 'updateManager'])->name("{$role}.branch_managers.update");
            Route::post('/branch-managers/{branchManager}/toggle', [BranchController::class, 'toggleManager'])->name("{$role}.branch_managers.toggle");
            Route::delete('/branch-managers/{branchManager}', [BranchController::class, 'destroyManager'])->name("{$role}.branch_managers.destroy");

            // Roles: /{role}/roles
            Route::get('/roles', function (Request $request) use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'superadmin';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        $qs = $request->getQueryString();

                        return redirect("/{$userRole}/roles".($qs ? "?{$qs}" : ''));
                    }
                }

                return app(RoleController::class)->index($request);
            })->name("{$role}.roles");
            Route::post('/roles', [RoleController::class, 'store'])->name("{$role}.roles.store");
            Route::put('/roles/{roleModel}', [RoleController::class, 'update'])->name("{$role}.roles.update");
            Route::post('/roles/{roleModel}/toggle', [RoleController::class, 'toggle'])->name("{$role}.roles.toggle");
            Route::delete('/roles/{roleModel}', [RoleController::class, 'destroy'])->name("{$role}.roles.destroy");

            // Permissions: /{role}/permissions
            Route::get('/permissions', function (Request $request) use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'superadmin';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        $qs = $request->getQueryString();

                        return redirect("/{$userRole}/permissions".($qs ? "?{$qs}" : ''));
                    }
                }

                return app(RoleController::class)->permissionsIndex($request);
            })->name("{$role}.permissions");
            Route::get('/permissions/{roleModel}', function (Request $request, $roleModel) use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'superadmin';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        return redirect("/{$userRole}/permissions?role_id={$roleModel}");
                    }
                }

                $request->merge(['role_id' => $roleModel]);

                return app(RoleController::class)->permissionsIndex($request);
            });
            Route::post('/permissions/{roleModel}', [RoleController::class, 'updatePermissions'])->name("{$role}.permissions.update");

            // Dedicated Page 1: Test Categories: /{role}/test-categories
            Route::get('/test-categories', function (Request $request) use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'laboratory';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        $qs = $request->getQueryString();

                        return redirect("/{$userRole}/test-categories".($qs ? "?{$qs}" : ''));
                    }
                }

                return app(LabTestController::class)->categoriesIndex($request);
            })->name("{$role}.test_categories");
            Route::post('/test-categories', [LabTestController::class, 'storeCategory'])->name("{$role}.test_categories.store");
            Route::post('/test-categories/{category}/verify', [LabTestController::class, 'verifyCategory'])->name("{$role}.test_categories.verify");
            Route::put('/test-categories/{category}', [LabTestController::class, 'updateCategory'])->name("{$role}.test_categories.update");
            Route::delete('/test-categories/{category}', [LabTestController::class, 'destroyCategory'])->name("{$role}.test_categories.destroy");

            // Dedicated Page 2: Lab Tests: /{role}/tests
            Route::get('/tests', function (Request $request) use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'laboratory';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        $qs = $request->getQueryString();

                        return redirect("/{$userRole}/tests".($qs ? "?{$qs}" : ''));
                    }
                }

                return app(LabTestController::class)->index($request);
            })->name("{$role}.tests");
            Route::post('/tests', [LabTestController::class, 'storeTest'])->name("{$role}.tests.store");
            Route::post('/tests/{labTest}/verify', [LabTestController::class, 'verifyTest'])->name("{$role}.tests.verify");
            Route::put('/tests/{labTest}', [LabTestController::class, 'updateTest'])->name("{$role}.tests.update");
            Route::delete('/tests/{labTest}', [LabTestController::class, 'destroyTest'])->name("{$role}.tests.destroy");

            // Dedicated Page 3: Test Fees & Pricing: /{role}/test-fees
            Route::get('/test-fees', function (Request $request) use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'laboratory';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        $qs = $request->getQueryString();

                        return redirect("/{$userRole}/test-fees".($qs ? "?{$qs}" : ''));
                    }
                }

                return app(LabTestController::class)->feesIndex($request);
            })->name("{$role}.test_fees");
            Route::post('/test-fees', [LabTestController::class, 'storeFee'])->name("{$role}.test_fees.store");
            Route::post('/test-fees/{labTest}/update-fee', [LabTestController::class, 'updateFee'])->name("{$role}.test_fees.update_fee");
            Route::post('/test-fees/bulk-update', [LabTestController::class, 'bulkUpdateFees'])->name("{$role}.test_fees.bulk_update");

            // 1-Click Sync All Categories, Tests & Fees across All Branches
            Route::post('/tests/sync-branches', [LabTestController::class, 'syncAllToBranches'])->name("{$role}.tests.sync_branches");

            // Staff Management: /{role}/staff
            Route::get('/staff', function (Request $request) use ($role) {
                if (Auth::check()) {
                    $userRole = Auth::user()->role_slug ?? 'laboratory';
                    if ($role === 'laboratory' && $userRole !== 'laboratory') {
                        $qs = $request->getQueryString();

                        return redirect("/{$userRole}/staff".($qs ? "?{$qs}" : ''));
                    }
                }

                return app(StaffController::class)->index($request);
            })->name("{$role}.staff");
            Route::post('/staff', [StaffController::class, 'store'])->name("{$role}.staff.store");
            Route::put('/staff/{staff}', [StaffController::class, 'update'])->name("{$role}.staff.update");
            Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name("{$role}.staff.destroy");

            // Master Management: /{role}/master/...
            Route::prefix('master')->group(function () use ($role) {
                // 1. Medical Types
                Route::get('/medical-types', [MasterController::class, 'medicalTypes'])->name("{$role}.master.medical_types");
                Route::post('/medical-types', [MasterController::class, 'storeMedicalType'])->name("{$role}.master.medical_types.store");
                Route::put('/medical-types/{medicalType}', [MasterController::class, 'updateMedicalType'])->name("{$role}.master.medical_types.update");
                Route::post('/medical-types/{medicalType}/toggle', [MasterController::class, 'toggleMedicalType'])->name("{$role}.master.medical_types.toggle");
                Route::delete('/medical-types/{medicalType}', [MasterController::class, 'destroyMedicalType'])->name("{$role}.master.medical_types.destroy");

                // 2. Hospital Types
                Route::get('/hospital-types', [MasterController::class, 'hospitalTypes'])->name("{$role}.master.hospital_types");
                Route::post('/hospital-types', [MasterController::class, 'storeHospitalType'])->name("{$role}.master.hospital_types.store");
                Route::put('/hospital-types/{hospitalType}', [MasterController::class, 'updateHospitalType'])->name("{$role}.master.hospital_types.update");
                Route::post('/hospital-types/{hospitalType}/toggle', [MasterController::class, 'toggleHospitalType'])->name("{$role}.master.hospital_types.toggle");
                Route::delete('/hospital-types/{hospitalType}', [MasterController::class, 'destroyHospitalType'])->name("{$role}.master.hospital_types.destroy");

                // 3. Laboratory Types
                Route::get('/laboratory-types', [MasterController::class, 'laboratoryTypes'])->name("{$role}.master.laboratory_types");
                Route::post('/laboratory-types', [MasterController::class, 'storeLaboratoryType'])->name("{$role}.master.laboratory_types.store");
                Route::put('/laboratory-types/{laboratoryType}', [MasterController::class, 'updateLaboratoryType'])->name("{$role}.master.laboratory_types.update");
                Route::post('/laboratory-types/{laboratoryType}/toggle', [MasterController::class, 'toggleLaboratoryType'])->name("{$role}.master.laboratory_types.toggle");
                Route::delete('/laboratory-types/{laboratoryType}', [MasterController::class, 'destroyLaboratoryType'])->name("{$role}.master.laboratory_types.destroy");

                // 4. Pharmacy Types
                Route::get('/pharmacy-types', [MasterController::class, 'pharmacyTypes'])->name("{$role}.master.pharmacy_types");
                Route::post('/pharmacy-types', [MasterController::class, 'storePharmacyType'])->name("{$role}.master.pharmacy_types.store");
                Route::put('/pharmacy-types/{pharmacyType}', [MasterController::class, 'updatePharmacyType'])->name("{$role}.master.pharmacy_types.update");
                Route::post('/pharmacy-types/{pharmacyType}/toggle', [MasterController::class, 'togglePharmacyType'])->name("{$role}.master.pharmacy_types.toggle");
                Route::delete('/pharmacy-types/{pharmacyType}', [MasterController::class, 'destroyPharmacyType'])->name("{$role}.master.pharmacy_types.destroy");
            });
        });
    }

    // Direct actions & fallback names for backwards compatibility
    Route::name('laboratory.')->group(function () {
        Route::post('/laboratory/test-categories', [LabTestController::class, 'storeCategory'])->name('test_categories.store');
        Route::delete('/laboratory/test-categories/{category}', [LabTestController::class, 'destroyCategory'])->name('test_categories.destroy');
        Route::post('/laboratory/test-fees', [LabTestController::class, 'storeFee'])->name('test_fees.store');
        Route::post('/laboratory/test-fees/bulk-update', [LabTestController::class, 'bulkUpdateFees'])->name('test_fees.bulk_update');
    });

    Route::post('/admin/hospitals', [DashboardController::class, 'storeHospital'])->name('hospitals.store');
    Route::put('/admin/hospitals/{hospital}', [DashboardController::class, 'updateHospital'])->name('hospitals.update');
    Route::post('/admin/hospitals/{hospital}/toggle', [DashboardController::class, 'toggleHospitalStatus'])->name('hospitals.toggle');
    Route::delete('/admin/hospitals/{hospital}', [DashboardController::class, 'destroyHospital'])->name('hospitals.destroy');

    Route::post('/master/medical-types', [MasterController::class, 'storeMedicalType'])->name('master.medical_types.store');
    Route::put('/master/medical-types/{medicalType}', [MasterController::class, 'updateMedicalType'])->name('master.medical_types.update');
    Route::post('/master/medical-types/{medicalType}/toggle', [MasterController::class, 'toggleMedicalType'])->name('master.medical_types.toggle');
    Route::delete('/master/medical-types/{medicalType}', [MasterController::class, 'destroyMedicalType'])->name('master.medical_types.destroy');

    Route::post('/master/hospital-types', [MasterController::class, 'storeHospitalType'])->name('master.hospital_types.store');
    Route::put('/master/hospital-types/{hospitalType}', [MasterController::class, 'updateHospitalType'])->name('master.hospital_types.update');
    Route::post('/master/hospital-types/{hospitalType}/toggle', [MasterController::class, 'toggleHospitalType'])->name('master.hospital_types.toggle');
    Route::delete('/master/hospital-types/{hospitalType}', [MasterController::class, 'destroyHospitalType'])->name('master.hospital_types.destroy');

    Route::post('/master/laboratory-types', [MasterController::class, 'storeLaboratoryType'])->name('master.laboratory_types.store');
    Route::put('/master/laboratory-types/{laboratoryType}', [MasterController::class, 'updateLaboratoryType'])->name('master.laboratory_types.update');
    Route::post('/master/laboratory-types/{laboratoryType}/toggle', [MasterController::class, 'toggleLaboratoryType'])->name('master.laboratory_types.toggle');
    Route::delete('/master/laboratory-types/{laboratoryType}', [MasterController::class, 'destroyLaboratoryType'])->name('master.laboratory_types.destroy');

    Route::post('/master/pharmacy-types', [MasterController::class, 'storePharmacyType'])->name('master.pharmacy_types.store');
    Route::put('/master/pharmacy-types/{pharmacyType}', [MasterController::class, 'updatePharmacyType'])->name('master.pharmacy_types.update');
    Route::post('/master/pharmacy-types/{pharmacyType}/toggle', [MasterController::class, 'togglePharmacyType'])->name('master.pharmacy_types.toggle');
    Route::delete('/master/pharmacy-types/{pharmacyType}', [MasterController::class, 'destroyPharmacyType'])->name('master.pharmacy_types.destroy');

    Route::put('/admin-permission/{hospital}', [AdminPermissionController::class, 'update'])->name('admin_permission.update');
    Route::post('/admin-permission/{hospital}/toggle', [AdminPermissionController::class, 'toggle'])->name('admin_permission.toggle');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
