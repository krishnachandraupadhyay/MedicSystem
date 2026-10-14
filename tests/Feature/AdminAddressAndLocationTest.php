<?php

namespace Tests\Feature;

use App\Models\Hospital;
use App\Models\MedicalType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAddressAndLocationTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'role' => 'Super Admin',
            'status' => 'Active',
        ]);

        MedicalType::create(['name' => 'Hospital', 'code' => 'MED-01', 'status' => 'Active']);
        MedicalType::create(['name' => 'Laboratory', 'code' => 'MED-02', 'status' => 'Active']);
        MedicalType::create(['name' => 'Pharmacy', 'code' => 'MED-03', 'status' => 'Active']);
    }

    public function test_super_admin_can_view_address_and_location_inputs_on_addadmin_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/superadmin/addadmin');

        $response->assertStatus(200);
        $response->assertSee('Hospital Address');
        $response->assertSee('Location / Landmark');
        $response->assertSee('name="address"', false);
        $response->assertSee('name="location"', false);
    }

    public function test_super_admin_can_create_hospital_with_address_and_location(): void
    {
        $response = $this->actingAs($this->superAdmin)->post('/superadmin/addadmin', [
            'medical_type' => 'Hospital',
            'type' => 'Multi-Specialty',
            'name' => 'Apollo Care Hospital',
            'email' => 'apollo@example.com',
            'phone' => '9876543210',
            'address' => '123 Health Boulevard, Sector 62',
            'location' => 'Near Metro Station Gate 2',
            'city' => 'Noida',
            'role' => 'Admin',
            'password' => 'hospital123',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/superadmin/addadmin');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('hospitals', [
            'name' => 'Apollo Care Hospital',
            'medical_type' => 'Hospital',
            'address' => '123 Health Boulevard, Sector 62',
            'location' => 'Near Metro Station Gate 2',
            'city' => 'Noida',
        ]);
    }

    public function test_super_admin_can_create_laboratory_with_address_and_location(): void
    {
        $response = $this->actingAs($this->superAdmin)->post('/superadmin/addadmin', [
            'medical_type' => 'Laboratory',
            'type' => 'Pathology Lab',
            'name' => 'Apex PathLabs',
            'email' => 'apexlabs@example.com',
            'phone' => '9812345678',
            'address' => 'Plot 45, Science City Tech Zone',
            'location' => 'Opposite Bio Research Center',
            'city' => 'Delhi',
            'role' => 'Admin',
            'password' => 'labpass123',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/superadmin/addadmin');

        $this->assertDatabaseHas('hospitals', [
            'name' => 'Apex PathLabs',
            'medical_type' => 'Laboratory',
            'address' => 'Plot 45, Science City Tech Zone',
            'location' => 'Opposite Bio Research Center',
            'city' => 'Delhi',
        ]);
    }

    public function test_super_admin_can_create_pharmacy_with_address_and_location(): void
    {
        $response = $this->actingAs($this->superAdmin)->post('/superadmin/addadmin', [
            'medical_type' => 'Pharmacy',
            'type' => 'Retail Pharmacy',
            'name' => 'CarePlus Meds',
            'email' => 'careplus@example.com',
            'phone' => '9800011122',
            'address' => 'Shop 12, Ground Floor, Central Market',
            'location' => 'Near City Tower Crossing',
            'city' => 'Gurugram',
            'role' => 'Admin',
            'password' => 'pharmapass123',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/superadmin/addadmin');

        $this->assertDatabaseHas('hospitals', [
            'name' => 'CarePlus Meds',
            'medical_type' => 'Pharmacy',
            'address' => 'Shop 12, Ground Floor, Central Market',
            'location' => 'Near City Tower Crossing',
            'city' => 'Gurugram',
        ]);
    }

    public function test_super_admin_can_update_facility_address_and_location(): void
    {
        $hospital = Hospital::create([
            'name' => 'Original Hospital',
            'email' => 'original@hospital.com',
            'medical_type' => 'Hospital',
            'city' => 'Old City',
            'address' => 'Old Address 123',
            'location' => 'Old Landmark',
            'status' => 'Active',
        ]);

        User::create([
            'name' => 'Original Hospital',
            'email' => 'original@hospital.com',
            'password' => 'secret123',
            'hospital_id' => $hospital->id,
            'role' => 'Admin',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->superAdmin)->put("/superadmin/addadmin/{$hospital->id}", [
            'name' => 'Updated Hospital',
            'email' => 'original@hospital.com',
            'medical_type' => 'Hospital',
            'city' => 'New City',
            'address' => 'New Facility Address 456',
            'location' => 'New Landmark Point',
            'status' => 'Active',
        ]);

        $response->assertRedirect('/superadmin/addadmin');

        $this->assertDatabaseHas('hospitals', [
            'id' => $hospital->id,
            'name' => 'Updated Hospital',
            'address' => 'New Facility Address 456',
            'location' => 'New Landmark Point',
            'city' => 'New City',
        ]);
    }
}
