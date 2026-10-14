<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchManager;
use App\Models\Hospital;
use App\Models\HospitalType;
use App\Models\LaboratoryType;
use App\Models\MedicalType;
use App\Models\PharmacyType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->delete();

        User::create([
            'id' => 8,
            'name' => 'Iron Paradise Admin',
            'hospital_name' => 'Iron Paradise Hospital',
            'email' => 'ironadmin@gmail.com',
            'password' => 'admin123',
            'role' => 'Admin',
            'status' => 'Active',
            'phone' => '+91 98765 43210',
            'created_at' => Carbon::parse('2026-10-11 10:00:00'),
        ]);

        User::create([
            'id' => 3,
            'name' => 'Super Admin',
            'hospital_name' => 'RK Hospital System',
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
            'role' => 'Super Admin',
            'role' => 'Super Admin',
            'status' => 'Active',
            'phone' => '+91 98111 22334',
            'created_at' => Carbon::parse('2026-10-11 11:30:00'),
        ]);

        User::create([
            'id' => 9,
            'name' => 'Apex Diagnostic Admin',
            'hospital_name' => 'Apex Diagnostic Center',
            'hospital_id' => 3,
            'email' => 'apexlab@gmail.com',
            'password' => 'admin123',
            'role' => 'Laboratory Admin',
            'status' => 'Active',
            'phone' => '+91 98111 22334',
            'created_at' => Carbon::parse('2026-10-11 11:30:00'),
        ]);

        Hospital::query()->delete();

        Hospital::create([
            'id' => 8,
            'name' => 'Iron Paradise Hospital',
            'code' => 'HOSP-01',
            'email' => 'ironadmin@gmail.com',
            'phone' => '+91 98765 43210',
            'city' => 'New Delhi',
            'medical_type' => 'Hospital',
            'type' => 'Multi-Specialty',
            'role' => 'Admin',
            'password' => 'admin123',
            'status' => 'Active',
            'enable_laboratory' => true,
            'enable_multi_branch' => true,
            'created_at' => Carbon::parse('2026-10-11 10:00:00'),
        ]);

        Hospital::create([
            'id' => 3,
            'name' => 'Apex Diagnostic Center',
            'code' => 'LAB-02',
            'email' => 'apexlab@gmail.com',
            'phone' => '+91 98111 22334',
            'city' => 'Mumbai',
            'medical_type' => 'Laboratory',
            'type' => 'Pathology Lab',
            'role' => 'Laboratory Admin',
            'password' => 'admin123',
            'status' => 'Active',
            'enable_laboratory' => true,
            'enable_multi_branch' => true,
            'created_at' => Carbon::parse('2026-10-11 11:30:00'),
        ]);

        Hospital::create([
            'id' => 4,
            'name' => 'CarePlus Medico Pharmacy',
            'code' => 'PHARM-03',
            'email' => 'careplus@gmail.com',
            'phone' => '+91 98222 33445',
            'city' => 'Bengaluru',
            'medical_type' => 'Pharmacy',
            'type' => 'Retail Pharmacy',
            'role' => 'Admin',
            'password' => 'admin123',
            'status' => 'Active',
            'enable_laboratory' => false,
            'enable_multi_branch' => false,
            'enable_pharmacy' => true,
            'created_at' => Carbon::parse('2026-10-11 14:00:00'),
        ]);

        Branch::query()->delete();
        Branch::create([
            'id' => 1,
            'hospital_id' => 8,
            'branch_code' => 'BR-01',
            'name' => 'Iron Paradise South Ext Branch',
            'city' => 'New Delhi',
            'phone' => '+91 98765 11111',
            'email' => 'southext@ironhospital.com',
            'address' => 'Ring Road, South Extension Part 1, New Delhi',
            'status' => 'Active',
        ]);

        BranchManager::query()->delete();
        BranchManager::create([
            'id' => 1,
            'hospital_id' => 8,
            'branch_id' => 1,
            'name' => 'Dr. Rajesh Sharma',
            'email' => 'rajesh.sharma@ironhospital.com',
            'phone' => '+91 98765 22222',
            'employee_id' => 'BM-01',
            'status' => 'Active',
        ]);

        MedicalType::query()->delete();
        MedicalType::create(['name' => 'Hospital', 'code' => 'MED-HOSP', 'description' => 'General & Specialized Hospital Facilities', 'status' => 'Active']);
        MedicalType::create(['name' => 'Laboratory', 'code' => 'MED-LAB', 'description' => 'Pathology and Diagnostic Laboratories', 'status' => 'Active']);
        MedicalType::create(['name' => 'Pharmacy', 'code' => 'MED-PHARM', 'description' => 'Retail and Clinical Pharmacies', 'status' => 'Active']);

        HospitalType::query()->delete();
        HospitalType::create(['name' => 'Multi-Specialty', 'code' => 'HT-MS', 'status' => 'Active']);
        HospitalType::create(['name' => 'Super Specialty', 'code' => 'HT-SS', 'status' => 'Active']);
        HospitalType::create(['name' => 'General Hospital', 'code' => 'HT-GH', 'status' => 'Active']);
        HospitalType::create(['name' => 'Clinic', 'code' => 'HT-CL', 'status' => 'Active']);
        HospitalType::create(['name' => 'Trauma Center', 'code' => 'HT-TC', 'status' => 'Active']);

        LaboratoryType::query()->delete();
        LaboratoryType::create(['name' => 'Pathology Lab', 'code' => 'LT-PL', 'status' => 'Active']);
        LaboratoryType::create(['name' => 'Diagnostic Center', 'code' => 'LT-DC', 'status' => 'Active']);
        LaboratoryType::create(['name' => 'Biochemistry Lab', 'code' => 'LT-BL', 'status' => 'Active']);
        LaboratoryType::create(['name' => 'Radiology & Imaging', 'code' => 'LT-RI', 'status' => 'Active']);
        LaboratoryType::create(['name' => 'Microbiology Lab', 'code' => 'LT-ML', 'status' => 'Active']);

        PharmacyType::query()->delete();
        PharmacyType::create(['name' => 'Retail Pharmacy', 'code' => 'PT-RP', 'description' => 'Independent walk-in chemist store', 'status' => 'Active']);
        PharmacyType::create(['name' => 'Hospital Pharmacy', 'code' => 'PT-HP', 'description' => 'In-house hospital IPD/OPD medical store', 'status' => 'Active']);
        PharmacyType::create(['name' => 'Clinical Pharmacy', 'code' => 'PT-CP', 'description' => 'Clinical care & specialized medicine center', 'status' => 'Active']);
        PharmacyType::create(['name' => 'Compounding Pharmacy', 'code' => 'PT-COMP', 'description' => 'Customized dosage preparation pharmacy', 'status' => 'Active']);
        PharmacyType::create(['name' => 'Online/Delivery Pharmacy', 'code' => 'PT-ONL', 'description' => 'E-pharmacy and home delivery pharmacy', 'status' => 'Active']);
    }
}
