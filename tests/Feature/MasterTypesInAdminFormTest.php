<?php

namespace Tests\Feature;

use App\Models\HospitalType;
use App\Models\LaboratoryType;
use App\Models\MedicalType;
use App\Models\PharmacyType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterTypesInAdminFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_active_master_types_are_displayed_in_admin_form(): void
    {
        // 1. Create a super admin user
        $user = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        // 2. Create Active and Inactive Medical Types
        MedicalType::create(['name' => 'Active Med Type', 'code' => 'MED-01', 'status' => 'Active']);
        MedicalType::create(['name' => 'Inactive Med Type', 'code' => 'MED-02', 'status' => 'Inactive']);

        // 3. Create Active and Inactive Hospital Types
        HospitalType::create(['name' => 'Active Hosp Type', 'code' => 'HOSP-01', 'status' => 'Active']);
        HospitalType::create(['name' => 'Inactive Hosp Type', 'code' => 'HOSP-02', 'status' => 'Inactive']);

        // 4. Create Active and Inactive Laboratory Types
        LaboratoryType::create(['name' => 'Active Lab Type', 'code' => 'LAB-01', 'status' => 'Active']);
        LaboratoryType::create(['name' => 'Inactive Lab Type', 'code' => 'LAB-02', 'status' => 'Inactive']);

        // 5. Create Active and Inactive Pharmacy Types
        PharmacyType::create(['name' => 'Active Pharm Type', 'code' => 'PHARM-01', 'status' => 'Active']);
        PharmacyType::create(['name' => 'Inactive Pharm Type', 'code' => 'PHARM-02', 'status' => 'Inactive']);

        // 6. Act: Visit /superadmin/addadmin as logged in Super Admin
        $response = $this->actingAs($user)->get('/superadmin/addadmin');

        // 7. Assert: Response is successful
        $response->assertStatus(200);

        // 8. Assert View data contains only active records
        $response->assertViewHas('medicalTypes', function ($types) {
            return $types->contains('name', 'Active Med Type') && ! $types->contains('name', 'Inactive Med Type');
        });

        $response->assertViewHas('hospitalTypes', function ($types) {
            return $types->contains('name', 'Active Hosp Type') && ! $types->contains('name', 'Inactive Hosp Type');
        });

        $response->assertViewHas('laboratoryTypes', function ($types) {
            return $types->contains('name', 'Active Lab Type') && ! $types->contains('name', 'Inactive Lab Type');
        });

        $response->assertViewHas('pharmacyTypes', function ($types) {
            return $types->contains('name', 'Active Pharm Type') && ! $types->contains('name', 'Inactive Pharm Type');
        });

        // 9. Assert HTML content contains Active types and does NOT contain Inactive types
        $response->assertSee('Active Med Type');
        $response->assertDontSee('Inactive Med Type');

        $response->assertSee('Active Hosp Type');
        $response->assertDontSee('Inactive Hosp Type');

        $response->assertSee('Active Lab Type');
        $response->assertDontSee('Inactive Lab Type');

        $response->assertSee('Active Pharm Type');
        $response->assertDontSee('Inactive Pharm Type');
    }
}
