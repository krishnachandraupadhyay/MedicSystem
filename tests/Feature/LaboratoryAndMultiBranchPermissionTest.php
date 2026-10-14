<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LaboratoryAndMultiBranchPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_laboratory_admin_can_login_when_laboratory_is_enabled(): void
    {
        $hospital = Hospital::create([
            'name' => 'Metro Diagnostic Lab',
            'code' => 'LAB-10',
            'email' => 'metrolab@example.com',
            'medical_type' => 'Laboratory',
            'status' => 'Active',
            'enable_laboratory' => true,
        ]);

        $user = User::create([
            'name' => 'Metro Lab Admin',
            'email' => 'metrolab@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Laboratory Admin',
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->name,
            'status' => 'Active',
        ]);

        $response = $this->post('/login', [
            'email' => 'metrolab@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/laboratory/dashboard');
        $this->assertAuthenticatedAs($user);

        // Also test visiting the laboratory dashboard
        $dashResponse = $this->actingAs($user)->get('/laboratory/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Metro Diagnostic Lab');
        $dashResponse->assertSee('Laboratory Module Active');
    }

    public function test_laboratory_admin_cannot_login_when_laboratory_is_disabled(): void
    {
        $hospital = Hospital::create([
            'name' => 'City Lab Center',
            'code' => 'LAB-20',
            'email' => 'citylab@example.com',
            'medical_type' => 'Laboratory',
            'status' => 'Active',
            'enable_laboratory' => false, // Disabled in Admin Permissions!
        ]);

        $user = User::create([
            'name' => 'City Lab Admin',
            'email' => 'citylab@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Laboratory Admin',
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->name,
            'status' => 'Active',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'citylab@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_can_add_branches_and_assign_branch_manager_when_multibranch_enabled(): void
    {
        $hospital = Hospital::create([
            'name' => 'Sunshine Hospital',
            'code' => 'HOSP-50',
            'email' => 'sunshine@example.com',
            'medical_type' => 'Hospital',
            'status' => 'Active',
            'enable_multi_branch' => true, // Enabled in Admin Permissions!
        ]);

        $user = User::create([
            'name' => 'Sunshine Admin',
            'email' => 'sunshine@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Admin',
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->name,
            'status' => 'Active',
        ]);

        // 1. Add Branch ("apna branch jod ke")
        $branchResponse = $this->actingAs($user)->post('/admin/branches', [
            'name' => 'Sunshine South Delhi Branch',
            'branch_code' => 'BR-SUN-01',
            'hospital_id' => $hospital->id,
            'city' => 'New Delhi',
            'phone' => '+91 99999 11111',
            'status' => 'Active',
        ]);

        $branchResponse->assertRedirect('/admin/branches');
        $this->assertDatabaseHas('branches', [
            'name' => 'Sunshine South Delhi Branch',
            'branch_code' => 'BR-SUN-01',
            'hospital_id' => $hospital->id,
        ]);

        $branch = Branch::where('branch_code', 'BR-SUN-01')->first();

        // 2. Assign Branch Manager ("branch manager assign kar dega")
        $mgrResponse = $this->actingAs($user)->post('/admin/branch-managers', [
            'name' => 'Dr. Sameer Verma',
            'branch_id' => $branch->id,
            'hospital_id' => $hospital->id,
            'email' => 'sameer.verma@sunshine.com',
            'phone' => '+91 99999 22222',
            'status' => 'Active',
        ]);

        $mgrResponse->assertRedirect('/admin/branch-managers');
        $this->assertDatabaseHas('branch_managers', [
            'name' => 'Dr. Sameer Verma',
            'branch_id' => $branch->id,
        ]);
    }

    public function test_cannot_add_branches_when_multibranch_is_disabled(): void
    {
        $hospital = Hospital::create([
            'name' => 'Single Unit Clinic',
            'code' => 'HOSP-60',
            'email' => 'clinic@example.com',
            'medical_type' => 'Hospital',
            'status' => 'Active',
            'enable_multi_branch' => false, // Disabled in Admin Permissions!
        ]);

        $user = User::create([
            'name' => 'Clinic Admin',
            'email' => 'clinic@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Admin',
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->name,
            'status' => 'Active',
        ]);

        $response = $this->actingAs($user)->post('/admin/branches', [
            'name' => 'Unauthorized Extra Branch',
            'hospital_id' => $hospital->id,
            'status' => 'Active',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('branches', [
            'name' => 'Unauthorized Extra Branch',
        ]);
    }

    public function test_laboratory_admin_can_add_their_own_branch_and_assign_manager(): void
    {
        $hospital = Hospital::create([
            'name' => 'Apex Diagnostic Center',
            'code' => 'LAB-02',
            'email' => 'apexlab@example.com',
            'medical_type' => 'Laboratory',
            'status' => 'Active',
            'enable_laboratory' => true,
            'enable_multi_branch' => true,
        ]);

        $user = User::create([
            'name' => 'Apex Lab Admin',
            'email' => 'apexlab@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Laboratory Admin',
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->name,
            'status' => 'Active',
        ]);

        // 1. Visit laboratory branches page
        $branchesPageResponse = $this->actingAs($user)->get('/laboratory/branches');
        $branchesPageResponse->assertStatus(200);
        $branchesPageResponse->assertSee('Apex Diagnostic Center');
        $branchesPageResponse->assertSee('Add Lab Branch');

        // 2. Add Laboratory Branch ("Lab Admin apne branch ko jodega")
        $branchResponse = $this->actingAs($user)->post('/laboratory/branches', [
            'name' => 'Apex Andheri Collection Center',
            'city' => 'Mumbai',
            'phone' => '+91 98111 55667',
            'address' => 'Plot 12, Andheri West, Mumbai',
            'status' => 'Active',
        ]);

        $branchResponse->assertRedirect('/laboratory/branches');
        $this->assertDatabaseHas('branches', [
            'name' => 'Apex Andheri Collection Center',
            'hospital_id' => $hospital->id,
            'city' => 'Mumbai',
        ]);

        $branch = Branch::where('name', 'Apex Andheri Collection Center')->first();
        $this->assertStringStartsWith('LAB-BR-', $branch->branch_code);

        // 3. Assign Branch Manager to this branch
        $mgrResponse = $this->actingAs($user)->post('/laboratory/branch-managers', [
            'name' => 'Dr. Pooja Sharma',
            'branch_id' => $branch->id,
            'email' => 'pooja.sharma@apexlab.com',
            'phone' => '+91 98111 77889',
            'status' => 'Active',
        ]);

        $mgrResponse->assertRedirect('/laboratory/branch-managers');
        $this->assertDatabaseHas('branch_managers', [
            'name' => 'Dr. Pooja Sharma',
            'branch_id' => $branch->id,
            'hospital_id' => $hospital->id,
        ]);
    }
}
