<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\BranchManager;
use App\Models\Hospital;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAndPermissionTest extends TestCase
{
    use RefreshDatabase;

    private Hospital $hospital;

    private User $labAdmin;

    private Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hospital = Hospital::create([
            'name' => 'Metro Diagnostic Lab',
            'code' => 'LAB-10',
            'email' => 'metrolab@example.com',
            'medical_type' => 'Laboratory',
            'status' => 'Active',
            'enable_laboratory' => true,
            'enable_multi_branch' => true,
        ]);

        $this->labAdmin = User::create([
            'name' => 'Metro Lab Admin',
            'email' => 'metrolab@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Laboratory Admin',
            'hospital_id' => $this->hospital->id,
            'hospital_name' => $this->hospital->name,
            'status' => 'Active',
        ]);

        $this->branch = Branch::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'North Branch',
            'branch_code' => 'BR-001',
            'phone' => '9876543210',
            'email' => 'north@metrolab.com',
            'city' => 'Delhi',
            'address' => 'Main Road, North Delhi',
            'status' => 'Active',
        ]);
    }

    public function test_lab_admin_can_view_roles_page(): void
    {
        $response = $this->actingAs($this->labAdmin)->get('/laboratory/roles');

        $response->assertStatus(200);
        $response->assertSee('Roles');
        $response->assertSee('Permissions');
        $response->assertSee('Create New Role');
    }

    public function test_lab_admin_can_create_role_with_granular_staff_and_attendance_permissions(): void
    {
        // User creates "Branch Manager" role with specific toggles:
        // Staff: view, create, edit, delete, attendance toggled
        // Attendance: view, create toggled
        $response = $this->actingAs($this->labAdmin)->post('/laboratory/roles', [
            'name' => 'Branch Manager',
            'description' => 'Manages North Branch operations and staff',
            'permissions' => [
                'staff' => [
                    'view' => '1',
                    'create' => '1',
                    'edit' => '1',
                    'delete' => '1',
                    'attendance' => '1',
                ],
                'attendance' => [
                    'view' => '1',
                    'create' => '1',
                ],
                'branches' => [
                    'view' => '1',
                ],
            ],
        ]);

        $response->assertRedirect('/laboratory/roles');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('roles', [
            'hospital_id' => $this->hospital->id,
            'name' => 'Branch Manager',
            'slug' => 'branch-manager',
            'status' => 'Active',
        ]);

        $role = Role::where('slug', 'branch-manager')->first();
        $this->assertNotNull($role);
        $this->assertTrue($role->hasPermission('staff', 'view'));
        $this->assertTrue($role->hasPermission('staff', 'create'));
        $this->assertTrue($role->hasPermission('staff', 'edit'));
        $this->assertTrue($role->hasPermission('staff', 'delete'));
        $this->assertTrue($role->hasPermission('staff', 'attendance'));
        $this->assertTrue($role->hasPermission('attendance', 'view'));
        $this->assertTrue($role->hasPermission('attendance', 'create'));

        // Non-assigned permissions must be false / inactive
        $this->assertFalse($role->hasPermission('branches', 'create'));
        $this->assertFalse($role->hasPermission('tests', 'delete'));
    }

    public function test_created_role_can_be_assigned_to_branch_manager(): void
    {
        // 1. Create Role
        $role = Role::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'Branch Manager',
            'slug' => 'branch-manager',
            'description' => 'Branch Operations Lead',
            'permissions' => [
                'staff' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'attendance' => true],
                'attendance' => ['view' => true, 'create' => true],
            ],
            'status' => 'Active',
        ]);

        // 2. View Branch Managers page, ensure role appears in option list
        $viewResponse = $this->actingAs($this->labAdmin)->get('/laboratory/branch-managers');
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Branch Manager');

        // 3. Create a branch manager assigned to this role
        $createManagerResponse = $this->actingAs($this->labAdmin)->post('/laboratory/branch-managers', [
            'branch_id' => $this->branch->id,
            'name' => 'Ramesh Sharma',
            'email' => 'ramesh.manager@metrolab.com',
            'phone' => '9876543210',
            'password' => 'manager123',
            'role_id' => $role->id,
            'status' => 'Active',
        ]);

        $createManagerResponse->assertRedirect('/laboratory/branch-managers');
        $this->assertDatabaseHas('branch_managers', [
            'hospital_id' => $this->hospital->id,
            'email' => 'ramesh.manager@metrolab.com',
            'role_id' => $role->id,
            'role_name' => 'Branch Manager',
        ]);

        $manager = BranchManager::where('email', 'ramesh.manager@metrolab.com')->first();
        $this->assertEquals($role->id, $manager->role->id);
        $this->assertEquals('Branch Manager', $manager->role->name);
    }

    public function test_roles_are_scoped_to_facility(): void
    {
        $otherHospital = Hospital::create([
            'name' => 'Other Diagnostics',
            'code' => 'LAB-99',
            'email' => 'other@diagnostics.com',
            'medical_type' => 'Laboratory',
            'status' => 'Active',
            'enable_laboratory' => true,
        ]);

        Role::create([
            'hospital_id' => $otherHospital->id,
            'name' => 'Secret Role',
            'slug' => 'secret-role',
            'permissions' => [],
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->labAdmin)->get('/laboratory/roles');
        $response->assertStatus(200);
        $response->assertDontSee('Secret Role');
    }

    public function test_branch_manager_account_is_created_with_password_and_can_login(): void
    {
        $role = Role::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'Center Head',
            'slug' => 'center-head',
            'permissions' => [
                'tests' => ['view' => true, 'create' => true],
            ],
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->labAdmin)->post('/laboratory/branch-managers', [
            'branch_id' => $this->branch->id,
            'name' => 'Kavita Patel',
            'email' => 'kavita@metrolab.com',
            'password' => 'kavitaSecret99',
            'phone' => '9876500000',
            'role_id' => $role->id,
            'status' => 'Active',
        ]);

        $response->assertRedirect('/laboratory/branch-managers');

        // Verify user account was created in users table
        $this->assertDatabaseHas('users', [
            'email' => 'kavita@metrolab.com',
            'name' => 'Kavita Patel',
            'role' => 'Center Head',
            'role_id' => $role->id,
            'hospital_id' => $this->hospital->id,
            'status' => 'Active',
        ]);

        $user = User::where('email', 'kavita@metrolab.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('kavitaSecret99', $user->password));

        // Test login
        $this->post('/logout');
        $loginResponse = $this->post('/login', [
            'email' => 'kavita@metrolab.com',
            'password' => 'kavitaSecret99',
        ]);
        $loginResponse->assertRedirect('/laboratory/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_role_work_and_permissions_assigned_by_lab_admin_are_strictly_enforced(): void
    {
        // 1. Lab Admin creates a custom role
        $role = Role::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'Specimen Collector',
            'slug' => 'specimen-collector',
            'permissions' => [
                'tests' => ['view' => true, 'create' => true, 'edit' => false, 'delete' => false],
                // No access to staff, branches, or patients
            ],
            'status' => 'Active',
        ]);

        // 2. Assign this role to a user
        $user = User::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'Deepak Verma',
            'email' => 'deepak@metrolab.com',
            'password' => Hash::make('password123'),
            'role' => 'Specimen Collector',
            'role_id' => $role->id,
            'status' => 'Active',
        ]);

        // 3. Verify user ONLY has work permissions assigned to their role
        $this->assertTrue($user->canAccessModule('tests'));
        $this->assertTrue($user->hasPermission('tests', 'create'));
        $this->assertFalse($user->hasPermission('tests', 'delete'));

        // 4. Verify user CANNOT access work that was not assigned to their role
        $this->assertFalse($user->canAccessModule('staff'));
        $this->assertFalse($user->hasPermission('staff', 'view'));
        $this->assertFalse($user->canAccessModule('branches'));
        $this->assertFalse($user->canAccessModule('patients'));

        // 5. Verify direct HTTP route authorization blocks them from unassigned work
        $staffResponse = $this->actingAs($user)->get('/laboratory/staff');
        $staffResponse->assertStatus(403);
    }
}
