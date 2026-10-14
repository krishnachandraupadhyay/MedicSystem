<?php

namespace Tests\Feature;

use App\Models\Hospital;
use App\Models\MedicalType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_permission_page_renders_with_facilities(): void
    {
        $user = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        $hospital = Hospital::create([
            'name' => 'City Care Hospital',
            'code' => 'HOSP-01',
            'medical_type' => 'Hospital',
            'type' => 'Multi-Specialty',
            'status' => 'Active',
            'enable_laboratory' => false,
            'enable_multi_branch' => false,
        ]);

        MedicalType::create(['name' => 'Hospital', 'code' => 'MED-01', 'status' => 'Active']);
        MedicalType::create(['name' => 'Laboratory', 'code' => 'MED-02', 'status' => 'Active']);

        $response = $this->actingAs($user)->get('/superadmin/admin-permission');

        $response->assertStatus(200);
        $response->assertSee('Admin Permissions');
        $response->assertSee('City Care Hospital');
        $response->assertSee('Laboratory Module');
        $response->assertSee('Multi-Branch System');
    }

    public function test_toggle_laboratory_permission_works_via_ajax(): void
    {
        $user = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        $hospital = Hospital::create([
            'name' => 'Metro Diagnostic Center',
            'code' => 'HOSP-02',
            'medical_type' => 'Hospital',
            'status' => 'Active',
            'enable_laboratory' => false,
            'enable_multi_branch' => false,
        ]);

        $response = $this->actingAs($user)
            ->postJson("/superadmin/admin-permission/{$hospital->id}/toggle", [
                'permission' => 'enable_laboratory',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'permission' => 'enable_laboratory',
            'value' => true,
        ]);

        $this->assertTrue($hospital->fresh()->enable_laboratory);
    }

    public function test_toggle_multi_branch_permission_works_via_ajax(): void
    {
        $user = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        $hospital = Hospital::create([
            'name' => 'Apex Healthcare Group',
            'code' => 'HOSP-03',
            'medical_type' => 'Hospital',
            'status' => 'Active',
            'enable_laboratory' => false,
            'enable_multi_branch' => false,
        ]);

        $response = $this->actingAs($user)
            ->postJson("/superadmin/admin-permission/{$hospital->id}/toggle", [
                'permission' => 'enable_multi_branch',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'permission' => 'enable_multi_branch',
            'value' => true,
        ]);

        $this->assertTrue($hospital->fresh()->enable_multi_branch);
    }

    public function test_update_all_permissions_via_put_request(): void
    {
        $user = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        $hospital = Hospital::create([
            'name' => 'Global Care Hospital',
            'code' => 'HOSP-04',
            'medical_type' => 'Hospital',
            'status' => 'Active',
            'enable_laboratory' => false,
            'enable_multi_branch' => false,
        ]);

        $response = $this->actingAs($user)
            ->put("/superadmin/admin-permission/{$hospital->id}", [
                'enable_laboratory' => '1',
                'enable_multi_branch' => '1',
                'enable_pharmacy' => '1',
            ]);

        $response->assertRedirect();
        $this->assertTrue($hospital->fresh()->enable_laboratory);
        $this->assertTrue($hospital->fresh()->enable_multi_branch);
        $this->assertTrue($hospital->fresh()->enable_pharmacy);
    }
}
