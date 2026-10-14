<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'enable_laboratory' => 'boolean',
            'enable_multi_branch' => 'boolean',
            'enable_pharmacy' => 'boolean',
        ];
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function branchManagers()
    {
        return $this->hasMany(BranchManager::class);
    }

    public function affiliatedHospital()
    {
        return $this->belongsTo(Hospital::class, 'affiliated_hospital_id');
    }

    public function affiliatedPharmacies()
    {
        return $this->hasMany(Hospital::class, 'affiliated_hospital_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function testCategories()
    {
        return $this->hasMany(TestCategory::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
