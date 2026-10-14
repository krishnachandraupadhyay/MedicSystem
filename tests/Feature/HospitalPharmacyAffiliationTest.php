<?php

namespace Tests\Feature;

use App\Models\Hospital;
use App\Models\HospitalType;
use App\Models\MedicalType;
use App\Models\PharmacyType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalPharmacyAffiliationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        MedicalType::create(['name' => 'Hospital', 'code' => 'MT-01', 'status' => 'Active']);
        MedicalType::create(['name' => 'Laboratory', 'code' => 'MT-02', 'status' => 'Active']);
        MedicalType::create(['name' => 'Pharmacy', 'code' => 'MT-03', 'status' => 'Active']);

        HospitalType::create(['name' => 'General Hospital', 'code' => 'HT-01', 'status' => 'Active']);
        PharmacyType::create(['name' => 'Hospital Pharmacy', 'code' => 'PT-01', 'status' => 'Active']);
        PharmacyType::create(['name' => 'Retail Pharmacy', 'code' => 'PT-02', 'status' => 'Active']);
    }

    public function test_admin_form_renders_existing_hospitals_in_pharmacy_affiliation_select(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        $hospital = Hospital::create([
            'name' => 'Apollo Care Hospital',
            'code' => 'HOSP-01',
            'medical_type' => 'Hospital',
            'type' => 'General Hospital',
            'city' => 'Mumbai',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($superAdmin)->get('/superadmin/addadmin');

        $response->assertStatus(200);
        $response->assertSee('Apollo Care Hospital');
        $response->assertSee('+ Other (Enter Hospital / Clinic)');
        $response->assertSee('Associated Hospital / Clinic');
    }

    public function test_can_create_hospital_pharmacy_linked_to_existing_hospital(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        $hospital = Hospital::create([
            'name' => 'Fortis MultiCare Hospital',
            'code' => 'HOSP-02',
            'medical_type' => 'Hospital',
            'type' => 'General Hospital',
            'city' => 'Delhi',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($superAdmin)->post('/superadmin/addadmin', [
            'name' => 'Fortis IPD Pharmacy',
            'email' => 'fortis.pharmacy@hospital.com',
            'phone' => '9876543210',
            'city' => 'Delhi',
            'medical_type' => 'Pharmacy',
            'type' => 'Hospital Pharmacy',
            'role' => 'Admin',
            'password' => 'secret123',
            'status' => 'Active',
            'affiliated_hospital_select' => (string) $hospital->id,
        ]);

        $response->assertRedirect('/superadmin/addadmin');

        $this->assertDatabaseHas('hospitals', [
            'name' => 'Fortis IPD Pharmacy',
            'medical_type' => 'Pharmacy',
            'type' => 'Hospital Pharmacy',
            'affiliated_hospital_id' => $hospital->id,
            'affiliated_hospital_name' => 'Fortis MultiCare Hospital',
        ]);
    }

    public function test_can_create_hospital_pharmacy_with_custom_hospital_name_via_other(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($superAdmin)->post('/superadmin/addadmin', [
            'name' => 'City Lifecare Chemist',
            'email' => 'citylife.chemist@gmail.com',
            'phone' => '9123456780',
            'city' => 'Bangalore',
            'medical_type' => 'Pharmacy',
            'type' => 'Hospital Pharmacy',
            'role' => 'Admin',
            'password' => 'secret123',
            'status' => 'Active',
            'affiliated_hospital_select' => 'other',
            'affiliated_hospital_custom_name' => 'St. Jude Children Clinic',
        ]);

        $response->assertRedirect('/superadmin/addadmin');

        $this->assertDatabaseHas('hospitals', [
            'name' => 'City Lifecare Chemist',
            'medical_type' => 'Pharmacy',
            'type' => 'Hospital Pharmacy',
            'affiliated_hospital_id' => null,
            'affiliated_hospital_name' => 'St. Jude Children Clinic',
        ]);
    }

    public function test_non_hospital_pharmacy_does_not_set_affiliated_hospital(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        $hospital = Hospital::create([
            'name' => 'Max Super Specialty Hospital',
            'code' => 'HOSP-03',
            'medical_type' => 'Hospital',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($superAdmin)->post('/superadmin/addadmin', [
            'name' => 'Downtown Retail Chemist',
            'email' => 'retail@chemist.com',
            'city' => 'Pune',
            'medical_type' => 'Pharmacy',
            'type' => 'Retail Pharmacy',
            'role' => 'Admin',
            'password' => 'secret123',
            'status' => 'Active',
            'affiliated_hospital_select' => (string) $hospital->id,
        ]);

        $response->assertRedirect('/superadmin/addadmin');

        $this->assertDatabaseHas('hospitals', [
            'name' => 'Downtown Retail Chemist',
            'type' => 'Retail Pharmacy',
            'affiliated_hospital_id' => null,
            'affiliated_hospital_name' => null,
        ]);
    }

    public function test_can_update_hospital_pharmacy_affiliation(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        $hosp1 = Hospital::create([
            'name' => 'Hospital One',
            'code' => 'HOSP-04',
            'medical_type' => 'Hospital',
            'status' => 'Active',
        ]);

        $hosp2 = Hospital::create([
            'name' => 'Hospital Two',
            'code' => 'HOSP-05',
            'medical_type' => 'Hospital',
            'status' => 'Active',
        ]);

        $pharmacy = Hospital::create([
            'name' => 'Old Central Pharmacy',
            'code' => 'HOSP-06',
            'medical_type' => 'Pharmacy',
            'type' => 'Hospital Pharmacy',
            'affiliated_hospital_id' => $hosp1->id,
            'affiliated_hospital_name' => 'Hospital One',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($superAdmin)->put("/superadmin/addadmin/{$pharmacy->id}", [
            'name' => 'Updated Central Pharmacy',
            'medical_type' => 'Pharmacy',
            'type' => 'Hospital Pharmacy',
            'status' => 'Active',
            'affiliated_hospital_select' => (string) $hosp2->id,
        ]);

        $response->assertRedirect('/superadmin/addadmin');

        $this->assertDatabaseHas('hospitals', [
            'id' => $pharmacy->id,
            'name' => 'Updated Central Pharmacy',
            'affiliated_hospital_id' => $hosp2->id,
            'affiliated_hospital_name' => 'Hospital Two',
        ]);
    }
}
