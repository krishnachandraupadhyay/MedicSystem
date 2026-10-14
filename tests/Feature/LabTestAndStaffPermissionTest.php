<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Hospital;
use App\Models\LabTest;
use App\Models\Role;
use App\Models\Staff;
use App\Models\TestCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LabTestAndStaffPermissionTest extends TestCase
{
    use RefreshDatabase;

    private Hospital $hospital;

    private User $labAdmin;

    private Branch $branch1;

    private Branch $branch2;

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

        $this->branch1 = Branch::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'North Branch',
            'branch_code' => 'BR-001',
            'phone' => '9876543210',
            'city' => 'Delhi',
            'address' => 'North Delhi',
            'status' => 'Active',
        ]);

        $this->branch2 = Branch::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'South Branch',
            'branch_code' => 'BR-002',
            'phone' => '9876543211',
            'city' => 'Delhi',
            'address' => 'South Delhi',
            'status' => 'Active',
        ]);
    }

    public function test_lab_admin_can_view_test_catalog_and_fees(): void
    {
        $response = $this->actingAs($this->labAdmin)->get('/laboratory/tests');

        $response->assertStatus(200);
        $response->assertSee('Diagnostic Tests');
        $response->assertSee('Fees Catalog');
    }

    public function test_lab_admin_can_add_category_and_test_with_fees(): void
    {
        // 1. Add Category
        $catResponse = $this->actingAs($this->labAdmin)->post('/laboratory/test-categories', [
            'name' => 'Hematology',
            'code' => 'CAT-HEM',
            'description' => 'Blood and tissue analysis',
            'branch_id' => $this->branch1->id,
            'status' => 'Active',
        ]);

        $catResponse->assertSessionHas('success');
        $this->assertDatabaseHas('test_categories', [
            'hospital_id' => $this->hospital->id,
            'name' => 'Hematology',
            'code' => 'CAT-HEM',
            'branch_id' => $this->branch1->id,
        ]);

        $category = TestCategory::where('name', 'Hematology')->first();

        // 2. Add Lab Test with Fees
        $testResponse = $this->actingAs($this->labAdmin)->post('/laboratory/tests', [
            'test_category_id' => $category->id,
            'name' => 'Complete Blood Count (CBC)',
            'code' => 'TST-CBC',
            'price' => '450.00',
            'sample_type' => 'Whole Blood',
            'turnaround_time' => '6 Hours',
            'branch_id' => $this->branch1->id,
            'status' => 'Active',
        ]);

        $testResponse->assertSessionHas('success');
        $this->assertDatabaseHas('lab_tests', [
            'hospital_id' => $this->hospital->id,
            'name' => 'Complete Blood Count (CBC)',
            'price' => 450.00,
            'branch_id' => $this->branch1->id,
        ]);
    }

    public function test_1_click_sync_applies_same_categories_tests_and_fees_to_all_branches(): void
    {
        // Create global category and test
        $category = TestCategory::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'Biochemistry',
            'code' => 'CAT-BIO',
            'status' => 'Active',
        ]);

        $test = LabTest::create([
            'hospital_id' => $this->hospital->id,
            'test_category_id' => $category->id,
            'name' => 'Liver Function Test (LFT)',
            'price' => 750.00,
            'status' => 'Active',
        ]);

        // Trigger 1-click sync
        $syncResponse = $this->actingAs($this->labAdmin)->post('/laboratory/tests/sync-branches');

        $syncResponse->assertSessionHas('success');

        // Both Branch 1 and Branch 2 must now have this test and category with exact price ₹750!
        $this->assertDatabaseHas('lab_tests', [
            'hospital_id' => $this->hospital->id,
            'branch_id' => $this->branch1->id,
            'name' => 'Liver Function Test (LFT)',
            'price' => 750.00,
        ]);

        $this->assertDatabaseHas('lab_tests', [
            'hospital_id' => $this->hospital->id,
            'branch_id' => $this->branch2->id,
            'name' => 'Liver Function Test (LFT)',
            'price' => 750.00,
        ]);
    }

    public function test_user_with_custom_role_granted_permission_can_manage_tests(): void
    {
        // 1. Create a role with tests.create permission
        $roleWithPerm = Role::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'Lab Technician',
            'slug' => 'lab-technician',
            'permissions' => [
                'tests' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false],
            ],
            'status' => 'Active',
        ]);

        // 2. Create user assigned to this role
        $techUser = User::create([
            'name' => 'Suresh Technician',
            'email' => 'suresh.tech@metrolab.com',
            'password' => Hash::make('password123'),
            'role' => 'Technician',
            'role_id' => $roleWithPerm->id,
            'hospital_id' => $this->hospital->id,
            'status' => 'Active',
        ]);

        // User should be able to view tests
        $viewResponse = $this->actingAs($techUser)->get('/laboratory/tests');
        $viewResponse->assertStatus(200);

        // User should be able to create category
        $createCatResponse = $this->actingAs($techUser)->post('/laboratory/test-categories', [
            'name' => 'Pathology',
            'status' => 'Active',
        ]);
        $createCatResponse->assertSessionHas('success');
    }

    public function test_user_without_tests_permission_cannot_add_tests(): void
    {
        // Role with NO test permissions
        $roleNoPerm = Role::create([
            'hospital_id' => $this->hospital->id,
            'name' => 'Front Desk',
            'slug' => 'front-desk',
            'permissions' => [
                'tests' => ['view' => false, 'create' => false],
            ],
            'status' => 'Active',
        ]);

        $deskUser = User::create([
            'name' => 'Desk Clerk',
            'email' => 'desk@metrolab.com',
            'password' => Hash::make('password123'),
            'role' => 'Front Desk',
            'role_id' => $roleNoPerm->id,
            'hospital_id' => $this->hospital->id,
            'status' => 'Active',
        ]);

        $response = $this->actingAs($deskUser)->post('/laboratory/test-categories', [
            'name' => 'Restricted Category',
            'status' => 'Active',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_staff_management_and_permission_enforcement(): void
    {
        // Lab Admin can view and create staff
        $staffResponse = $this->actingAs($this->labAdmin)->post('/laboratory/staff', [
            'name' => 'Dr. Ananya Ray',
            'designation' => 'Consultant Pathologist',
            'qualification' => 'MBBS, MD',
            'email' => 'ananya@metrolab.com',
            'phone' => '9876500000',
            'employee_id' => 'EMP-001',
            'branch_id' => $this->branch1->id,
            'status' => 'Active',
        ]);

        $staffResponse->assertSessionHas('success');
        $this->assertDatabaseHas('staff', [
            'hospital_id' => $this->hospital->id,
            'name' => 'Dr. Ananya Ray',
            'employee_id' => 'EMP-001',
            'branch_id' => $this->branch1->id,
        ]);
    }
}
