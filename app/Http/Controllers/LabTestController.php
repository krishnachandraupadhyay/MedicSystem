<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchManager;
use App\Models\Hospital;
use App\Models\LabTest;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabTestController extends Controller
{
    /**
     * Resolve current Facility Context (Hospital / Lab) with strict tenant isolation
     */
    protected function getFacilityContext(): array
    {
        $user = Auth::user();
        $isSuperAdmin = ($user->role === 'Super Admin');

        $hospital = null;
        if (! empty($user->hospital_id)) {
            $hospital = Hospital::find($user->hospital_id);
        }
        if (! $hospital && $user->hospital) {
            $hospital = $user->hospital;
        }
        if (! $hospital) {
            $hospital = Hospital::where('email', $user->email)->first()
                ?? Hospital::where('name', $user->hospital_name)->first();
        }
        if (! $hospital) {
            $manager = BranchManager::where('email', $user->email)->first();
            if ($manager) {
                $hospital = $manager->hospital ?? ($manager->branch ? $manager->branch->hospital : null);
            }
        }
        if (! $hospital && $isSuperAdmin) {
            $hospital = Hospital::where('type', 'like', '%Lab%')->first() ?? Hospital::first();
        }

        return [$hospital, $isSuperAdmin];
    }

    /**
     * Permission authorization check for tests module
     */
    protected function authorizeTestPermission(string $action = 'view'): bool
    {
        return Auth::user()->hasPermission('tests', $action);
    }

    /**
     * Dedicated Page 1: Test Categories
     */
    public function categoriesIndex(Request $request)
    {
        if (! $this->authorizeTestPermission('view')) {
            abort(403, 'Unauthorized: You do not have permission to view test categories.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if (! $hospital) {
            abort(403, 'Unauthorized: No laboratory facility found for this user account.');
        }

        $search = $request->query('search');
        $selectedBranchId = $request->query('branch_id');

        // STRICT TENANT ISOLATION: Only branches created by THIS laboratory
        $branches = Branch::where('hospital_id', $hospital->id)->where('status', 'Active')->orderBy('name')->get();

        // STRICT TENANT ISOLATION: Only categories created by THIS laboratory
        $categoriesQuery = TestCategory::where('hospital_id', $hospital->id)->withCount('tests')->with('branch');

        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $userBranchId = Auth::user()->branch->id;
            $selectedBranchId = $userBranchId;
            $categoriesQuery->where(function ($q) use ($userBranchId) {
                $q->where('branch_id', $userBranchId)
                    ->orWhereNull('branch_id'); // Global within THIS facility
            })->where('status', 'Active');
        } elseif ($selectedBranchId !== null && $selectedBranchId !== '') {
            $categoriesQuery->where(function ($q) use ($selectedBranchId) {
                $q->where('branch_id', $selectedBranchId)
                    ->orWhereNull('branch_id'); // Global within THIS facility
            });
        }
        if ($search) {
            $categoriesQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $categoriesQuery->orderBy('name')->get();

        $stats = [
            'total_categories' => $categories->count(),
            'active_categories' => $categories->where('status', 'Active')->count(),
            'total_tests_linked' => $categories->sum('tests_count'),
            'branches_count' => $branches->count(),
        ];

        $canCreate = $this->authorizeTestPermission('create');
        $canEdit = $this->authorizeTestPermission('edit');
        $canDelete = $this->authorizeTestPermission('delete');

        return view('laboratory.categories.index', compact(
            'hospital',
            'branches',
            'categories',
            'stats',
            'search',
            'selectedBranchId',
            'canCreate',
            'canEdit',
            'canDelete'
        ));
    }

    /**
     * Dedicated Page 2: Diagnostic Lab Tests Catalog
     */
    public function index(Request $request)
    {
        if (! $this->authorizeTestPermission('view')) {
            abort(403, 'Unauthorized: You do not have permission to view diagnostic tests.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if (! $hospital) {
            abort(403, 'Unauthorized: No laboratory facility found for this user account.');
        }

        $search = $request->query('search');
        $selectedBranchId = $request->query('branch_id');
        $selectedCategoryId = $request->query('category_id');

        // STRICT TENANT ISOLATION: Only branches created by THIS laboratory
        $branches = Branch::where('hospital_id', $hospital->id)->where('status', 'Active')->orderBy('name')->get();

        // STRICT TENANT ISOLATION: Only categories created by THIS laboratory
        $categoriesQuery = TestCategory::where('hospital_id', $hospital->id)->with('branch');
        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $userBranchId = Auth::user()->branch->id;
            $selectedBranchId = $userBranchId;
            $categoriesQuery->where(function ($q) use ($userBranchId) {
                $q->where('branch_id', $userBranchId)
                    ->orWhereNull('branch_id');
            });
        } elseif ($selectedBranchId !== null && $selectedBranchId !== '') {
            $categoriesQuery->where(function ($q) use ($selectedBranchId) {
                $q->where('branch_id', $selectedBranchId)
                    ->orWhereNull('branch_id');
            });
        }
        $categories = $categoriesQuery->orderBy('name')->get();

        // STRICT TENANT ISOLATION: Only tests created by THIS laboratory
        $testsQuery = LabTest::where('hospital_id', $hospital->id)->with(['category', 'branch']);
        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $userBranchId = Auth::user()->branch->id;
            $testsQuery->where(function ($q) use ($userBranchId) {
                $q->where('branch_id', $userBranchId)
                    ->orWhereNull('branch_id');
            })->where('status', 'Active');
        } elseif ($selectedBranchId !== null && $selectedBranchId !== '') {
            $testsQuery->where(function ($q) use ($selectedBranchId) {
                $q->where('branch_id', $selectedBranchId)
                    ->orWhereNull('branch_id');
            });
        }
        if ($selectedCategoryId) {
            $testsQuery->where('test_category_id', $selectedCategoryId);
        }
        if ($search) {
            $testsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('sample_type', 'like', "%{$search}%")
                    ->orWhere('turnaround_time', 'like', "%{$search}%");
            });
        }

        $tests = $testsQuery->orderBy('name')->get();

        // Stats calculation
        $stats = [
            'total_tests' => $tests->count(),
            'total_categories' => $categories->count(),
            'active_tests' => $tests->where('status', 'Active')->count(),
            'branches_count' => $branches->count(),
        ];

        // Permissions flag for UI buttons
        $canCreate = $this->authorizeTestPermission('create');
        $canEdit = $this->authorizeTestPermission('edit');
        $canDelete = $this->authorizeTestPermission('delete');

        return view('laboratory.tests.index', compact(
            'hospital',
            'branches',
            'categories',
            'tests',
            'stats',
            'search',
            'selectedBranchId',
            'selectedCategoryId',
            'canCreate',
            'canEdit',
            'canDelete'
        ));
    }

    /**
     * Store Test Category
     */
    public function storeCategory(Request $request)
    {
        if (! $this->authorizeTestPermission('create')) {
            return back()->with('error', 'You do not have permission to add test categories.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'branch_id' => ['nullable'],
            'apply_to_all_branches' => ['nullable', 'boolean'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $isAdmin = in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin']);
        $initialStatus = $isAdmin ? ($validated['status'] ?? 'Active') : 'Pending Verification';

        $applyAll = $isAdmin && ($request->boolean('apply_to_all_branches') || empty($validated['branch_id']));
        $branchId = (Auth::user()->is_branch_manager && Auth::user()->branch)
            ? Auth::user()->branch->id
            : ($applyAll ? null : $validated['branch_id']);

        $category = TestCategory::create([
            'hospital_id' => $hospital?->id,
            'branch_id' => $branchId,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $initialStatus,
        ]);

        // If user wants copies explicitly in each branch table
        if ($applyAll && $hospital) {
            $branches = $hospital->branches()->where('status', 'Active')->get();
            foreach ($branches as $branch) {
                TestCategory::firstOrCreate([
                    'hospital_id' => $hospital->id,
                    'branch_id' => $branch->id,
                    'name' => $validated['name'],
                ], [
                    'code' => $validated['code'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'status' => $initialStatus,
                ]);
            }
        }

        if (! $isAdmin) {
            return back()->with('success', "Test Category '{$category->name}' submitted! It is pending Laboratory Admin verification before branch activation.");
        }

        return back()->with('success', "Test Category '{$category->name}' created successfully!");
    }

    /**
     * Update Test Category
     */
    public function updateCategory(Request $request, TestCategory $category)
    {
        if (! $this->authorizeTestPermission('edit')) {
            return back()->with('error', 'You do not have permission to edit test categories.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if ($category->hospital_id !== $hospital?->id) {
            abort(403, 'Unauthorized to modify category from another laboratory facility.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'branch_id' => ['nullable'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        if (! empty($validated['branch_id'])) {
            Branch::where('id', $validated['branch_id'])->where('hospital_id', $hospital->id)->firstOrFail();
        }

        $category->update([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'description' => $validated['description'] ?? null,
            'branch_id' => ! empty($validated['branch_id']) ? $validated['branch_id'] : null,
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Test Category '{$category->name}' updated successfully!");
    }

    /**
     * Destroy Test Category
     */
    public function destroyCategory(TestCategory $category)
    {
        if (! $this->authorizeTestPermission('delete')) {
            return back()->with('error', 'You do not have permission to delete test categories.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if ($category->hospital_id !== $hospital?->id) {
            abort(403, 'Unauthorized to delete category from another laboratory facility.');
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', "Test Category '{$name}' removed successfully.");
    }

    /**
     * Store Lab Test with Price/Fees & Branch Assignment
     */
    public function storeTest(Request $request)
    {
        if (! $this->authorizeTestPermission('create')) {
            return back()->with('error', 'You do not have permission to create diagnostic tests.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        $validated = $request->validate([
            'test_category_id' => ['required', 'exists:test_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'sample_type' => ['nullable', 'string', 'max:100'],
            'turnaround_time' => ['nullable', 'string', 'max:100'],
            'method' => ['nullable', 'string', 'max:100'],
            'branch_id' => ['nullable'],
            'sync_all_branches' => ['nullable'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $isAdmin = in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin']);
        $initialStatus = $isAdmin ? ($validated['status'] ?? 'Active') : 'Pending Verification';

        $category = TestCategory::where('id', $validated['test_category_id'])->where('hospital_id', $hospital->id)->firstOrFail();
        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $branchId = Auth::user()->branch->id;
            $syncAll = false;
        } else {
            $chosenBranch = ! empty($validated['branch_id']) ? $validated['branch_id'] : $category->branch_id;
            $syncAll = $isAdmin && ($request->has('sync_all_branches') || empty($chosenBranch));
            $branchId = $syncAll ? null : $chosenBranch;
        }
        $price = $validated['price'] ?? 0.00;

        $test = LabTest::create([
            'hospital_id' => $hospital?->id,
            'branch_id' => $branchId,
            'test_category_id' => $validated['test_category_id'],
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'price' => $price,
            'sample_type' => $validated['sample_type'] ?? null,
            'turnaround_time' => $validated['turnaround_time'] ?? null,
            'method' => $validated['method'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $initialStatus,
        ]);

        // If user asked to apply to all branches immediately:
        if ($syncAll && $hospital) {
            $branches = $hospital->branches()->where('status', 'Active')->get();
            $category = TestCategory::find($validated['test_category_id']);

            foreach ($branches as $b) {
                // Find matching category in this branch or use master
                $branchCat = TestCategory::where('hospital_id', $hospital->id)
                    ->where('name', $category?->name)
                    ->where('branch_id', $b->id)
                    ->first() ?? $category;

                LabTest::updateOrCreate([
                    'hospital_id' => $hospital->id,
                    'branch_id' => $b->id,
                    'name' => $validated['name'],
                ], [
                    'code' => $validated['code'] ?? null,
                    'test_category_id' => $branchCat?->id ?? $validated['test_category_id'],
                    'price' => $price,
                    'sample_type' => $validated['sample_type'] ?? null,
                    'turnaround_time' => $validated['turnaround_time'] ?? null,
                    'method' => $validated['method'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'status' => $initialStatus,
                ]);
            }
        }

        if (! $isAdmin) {
            return back()->with('success', "Test '{$test->name}' created with fee ₹{$price}! It is pending Laboratory Admin verification and will appear in your branch once approved.");
        }

        return back()->with('success', "Lab Test '{$test->name}' added successfully!");
    }

    /**
     * Update Lab Test
     */
    public function updateTest(Request $request, LabTest $labTest)
    {
        if (! $this->authorizeTestPermission('edit')) {
            return back()->with('error', 'You do not have permission to edit diagnostic tests.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if ($labTest->hospital_id !== $hospital?->id) {
            abort(403, 'Unauthorized to modify test from another laboratory facility.');
        }

        $validated = $request->validate([
            'test_category_id' => ['required', 'exists:test_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'sample_type' => ['nullable', 'string', 'max:100'],
            'turnaround_time' => ['nullable', 'string', 'max:100'],
            'method' => ['nullable', 'string', 'max:100'],
            'branch_id' => ['nullable'],
            'sync_all_branches' => ['nullable'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        // Verify category belongs to this laboratory
        TestCategory::where('id', $validated['test_category_id'])->where('hospital_id', $hospital->id)->firstOrFail();
        if (! empty($validated['branch_id'])) {
            Branch::where('id', $validated['branch_id'])->where('hospital_id', $hospital->id)->firstOrFail();
        }

        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $syncAll = false;
            $branchId = Auth::user()->branch->id;
        } else {
            $syncAll = $request->has('sync_all_branches');
            $branchId = ! empty($validated['branch_id']) ? $validated['branch_id'] : null;
        }
        $price = array_key_exists('price', $validated) && $validated['price'] !== null ? $validated['price'] : $labTest->price;

        $labTest->update([
            'test_category_id' => $validated['test_category_id'],
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'price' => $price,
            'sample_type' => $validated['sample_type'] ?? null,
            'turnaround_time' => $validated['turnaround_time'] ?? null,
            'method' => $validated['method'] ?? null,
            'branch_id' => $branchId,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        if ($syncAll && $labTest->hospital_id) {
            $branches = Branch::where('hospital_id', $hospital->id)->where('status', 'Active')->get();
            foreach ($branches as $b) {
                LabTest::updateOrCreate([
                    'hospital_id' => $hospital->id,
                    'branch_id' => $b->id,
                    'name' => $validated['name'],
                ], [
                    'code' => $validated['code'] ?? null,
                    'test_category_id' => $validated['test_category_id'],
                    'price' => $price,
                    'sample_type' => $validated['sample_type'] ?? null,
                    'turnaround_time' => $validated['turnaround_time'] ?? null,
                    'method' => $validated['method'] ?? null,
                    'status' => $validated['status'],
                ]);
            }
        }

        return back()->with('success', "Lab Test '{$labTest->name}' updated successfully!");
    }

    /**
     * Destroy Lab Test
     */
    public function destroyTest(LabTest $labTest)
    {
        if (! $this->authorizeTestPermission('delete')) {
            return back()->with('error', 'You do not have permission to delete diagnostic tests.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if ($labTest->hospital_id !== $hospital?->id) {
            abort(403, 'Unauthorized to delete test from another laboratory facility.');
        }

        $name = $labTest->name;
        $labTest->delete();

        return back()->with('success', "Test '{$name}' deleted successfully.");
    }

    /**
     * 1-Click Sync All Categories, Tests & Fees to All Branches
     * Requirement: "yaisa bhi option hone ki agar same type ke category and test and test fees har branch me ho click karte hi sab me aa jaye"
     */
    public function syncAllToBranches(Request $request)
    {
        if (! $this->authorizeTestPermission('create')) {
            return back()->with('error', 'You do not have permission to sync tests across branches.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();

        if (! $hospital) {
            return back()->with('error', 'Facility context not found.');
        }

        $branches = $hospital->branches()->where('status', 'Active')->get();

        if ($branches->isEmpty()) {
            return back()->with('error', 'No active branches found to sync with. Please add branches first.');
        }

        // Get master categories & tests
        $allCategories = TestCategory::where('hospital_id', $hospital->id)->get();
        $allTests = LabTest::where('hospital_id', $hospital->id)->get();

        if ($allTests->isEmpty() && $allCategories->isEmpty()) {
            return back()->with('error', 'No tests or categories available to sync. Please add at least one test first.');
        }

        $syncedCount = 0;

        foreach ($branches as $branch) {
            // 1. Sync Categories for this branch
            $categoryMap = [];
            foreach ($allCategories as $cat) {
                $branchCat = TestCategory::firstOrCreate([
                    'hospital_id' => $hospital->id,
                    'branch_id' => $branch->id,
                    'name' => $cat->name,
                ], [
                    'code' => $cat->code,
                    'description' => $cat->description,
                    'status' => $cat->status,
                ]);
                $categoryMap[$cat->id] = $branchCat->id;
            }

            // 2. Sync Tests & Exact Fees for this branch
            foreach ($allTests as $t) {
                $targetCatId = $categoryMap[$t->test_category_id] ?? $t->test_category_id;

                LabTest::updateOrCreate([
                    'hospital_id' => $hospital->id,
                    'branch_id' => $branch->id,
                    'name' => $t->name,
                ], [
                    'code' => $t->code,
                    'test_category_id' => $targetCatId,
                    'price' => $t->price,
                    'sample_type' => $t->sample_type,
                    'turnaround_time' => $t->turnaround_time,
                    'method' => $t->method,
                    'description' => $t->description,
                    'status' => $t->status,
                ]);
                $syncedCount++;
            }
        }

        return back()->with('success', "⚡ Success! All categories, tests, and fees have been synchronized across all {$branches->count()} branches ({$syncedCount} branch test records synchronized).");
    }

    /**
     * Dedicated Page 3: Test Fees & Pricing Management
     */
    public function feesIndex(Request $request)
    {
        if (! $this->authorizeTestPermission('view')) {
            abort(403, 'Unauthorized: You do not have permission to view test pricing.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if (! $hospital) {
            abort(403, 'Unauthorized: Facility context missing.');
        }

        $search = $request->query('search');
        $selectedBranchId = $request->query('branch_id');
        $selectedCategoryId = $request->query('category_id');

        // STRICT TENANT ISOLATION: Only branches and categories of THIS laboratory
        $branches = Branch::where('hospital_id', $hospital->id)->where('status', 'Active')->orderBy('name')->get();
        $categories = TestCategory::where('hospital_id', $hospital->id)->orderBy('name')->get();

        // STRICT TENANT ISOLATION: Only tests of THIS laboratory
        $testsQuery = LabTest::where('hospital_id', $hospital->id)->with(['category', 'branch']);
        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $userBranchId = Auth::user()->branch->id;
            $selectedBranchId = $userBranchId;
            $testsQuery->where(function ($q) use ($userBranchId) {
                $q->where('branch_id', $userBranchId)
                    ->orWhereNull('branch_id');
            });
        } elseif ($selectedBranchId !== null && $selectedBranchId !== '') {
            $testsQuery->where(function ($q) use ($selectedBranchId) {
                $q->where('branch_id', $selectedBranchId)
                    ->orWhereNull('branch_id');
            });
        }
        if ($selectedCategoryId) {
            $testsQuery->where('test_category_id', $selectedCategoryId);
        }
        if ($search) {
            $testsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $tests = $testsQuery->orderBy('name')->get();

        $stats = [
            'total_tests' => $tests->count(),
            'avg_fee' => $tests->count() > 0 ? round($tests->avg('price'), 2) : 0,
            'max_fee' => $tests->count() > 0 ? $tests->max('price') : 0,
            'min_fee' => $tests->count() > 0 ? $tests->min('price') : 0,
            'branches_count' => $branches->count(),
        ];

        $canEdit = $this->authorizeTestPermission('edit');

        return view('laboratory.fees.index', compact(
            'hospital',
            'branches',
            'categories',
            'tests',
            'stats',
            'search',
            'selectedBranchId',
            'selectedCategoryId',
            'canEdit'
        ));
    }

    /**
     * Store or Set Test Fee (Dedicated Fee Form)
     */
    public function storeFee(Request $request)
    {
        if (! $this->authorizeTestPermission('edit')) {
            return back()->with('error', 'You do not have permission to manage test fees.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if (! $hospital) {
            abort(403, 'Unauthorized: Facility context missing.');
        }

        $validated = $request->validate([
            'lab_test_id' => ['required', 'exists:lab_tests,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'branch_id' => ['nullable'],
            'sync_all_branches' => ['nullable'],
        ]);

        $test = LabTest::where('id', $validated['lab_test_id'])->where('hospital_id', $hospital->id)->firstOrFail();
        $oldPrice = $test->price;
        $test->update(['price' => $validated['price']]);

        if ($request->has('sync_all_branches') && $test->hospital_id) {
            LabTest::where('hospital_id', $test->hospital_id)
                ->where('name', $test->name)
                ->update(['price' => $validated['price']]);
        }

        return back()->with('success', "Fee for '{$test->name}' set to ₹{$validated['price']} successfully!");
    }

    /**
     * Update Single Test Fee
     */
    public function updateFee(Request $request, LabTest $labTest)
    {
        if (! $this->authorizeTestPermission('edit')) {
            return back()->with('error', 'You do not have permission to edit test fees.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if ($labTest->hospital_id !== $hospital?->id) {
            abort(403, 'Unauthorized to modify fee of another laboratory.');
        }

        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'sync_all_branches' => ['nullable'],
        ]);

        $oldPrice = $labTest->price;
        $labTest->update([
            'price' => $validated['price'],
        ]);

        if ($request->has('sync_all_branches') && $labTest->hospital_id) {
            LabTest::where('hospital_id', $labTest->hospital_id)
                ->where('name', $labTest->name)
                ->update(['price' => $validated['price']]);
        }

        return back()->with('success', "Fee for '{$labTest->name}' updated from ₹{$oldPrice} to ₹{$validated['price']} successfully!");
    }

    /**
     * Bulk Adjust / Update Fees across tests and branches
     */
    public function bulkUpdateFees(Request $request)
    {
        if (! $this->authorizeTestPermission('edit')) {
            return back()->with('error', 'You do not have permission to edit test fees.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if (! $hospital) {
            return back()->with('error', 'Hospital facility not found.');
        }

        $validated = $request->validate([
            'category_id' => ['nullable'],
            'branch_id' => ['nullable'],
            'adjustment_type' => ['required', 'in:percentage,fixed'],
            'adjustment_value' => ['required', 'numeric'],
            'action' => ['required', 'in:increase,decrease,set'],
        ]);

        $query = LabTest::where('hospital_id', $hospital->id);
        if (! empty($validated['category_id'])) {
            $query->where('test_category_id', $validated['category_id']);
        }
        if (Auth::user()->is_branch_manager && Auth::user()->branch) {
            $query->where('branch_id', Auth::user()->branch->id);
        } elseif ($validated['branch_id'] !== null && $validated['branch_id'] !== '') {
            $query->where('branch_id', $validated['branch_id']);
        }

        $tests = $query->get();
        $updatedCount = 0;

        foreach ($tests as $test) {
            $currentPrice = (float) $test->price;
            $val = (float) $validated['adjustment_value'];

            if ($validated['adjustment_type'] === 'percentage') {
                if ($validated['action'] === 'increase') {
                    $newPrice = $currentPrice + ($currentPrice * ($val / 100));
                } elseif ($validated['action'] === 'decrease') {
                    $newPrice = max(0, $currentPrice - ($currentPrice * ($val / 100)));
                } else {
                    $newPrice = $currentPrice;
                }
            } else {
                if ($validated['action'] === 'increase') {
                    $newPrice = $currentPrice + $val;
                } elseif ($validated['action'] === 'decrease') {
                    $newPrice = max(0, $currentPrice - $val);
                } else {
                    $newPrice = max(0, $val);
                }
            }

            $test->update(['price' => round($newPrice, 2)]);
            $updatedCount++;
        }

        return back()->with('success', "Updated pricing for {$updatedCount} test(s) successfully!");
    }

    /**
     * Verify & Approve Test for assigned branch (Laboratory Admin only)
     */
    public function verifyTest(LabTest $labTest)
    {
        $user = Auth::user();
        if (! in_array($user->role, ['Super Admin', 'Laboratory Admin', 'Admin'])) {
            abort(403, 'Unauthorized: Only Laboratory Admin can verify tests.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if ($labTest->hospital_id !== $hospital?->id) {
            abort(403, 'Unauthorized to verify test belonging to another facility.');
        }

        $labTest->update(['status' => 'Active']);

        $branchName = $labTest->branch?->name ?? 'All Branches';

        return back()->with('success', "Test '{$labTest->name}' has been verified & approved! It is now active in {$branchName}.");
    }

    /**
     * Verify & Approve Category (Laboratory Admin only)
     */
    public function verifyCategory(TestCategory $category)
    {
        $user = Auth::user();
        if (! in_array($user->role, ['Super Admin', 'Laboratory Admin', 'Admin'])) {
            abort(403, 'Unauthorized: Only Laboratory Admin can verify test categories.');
        }

        [$hospital, $isSuperAdmin] = $this->getFacilityContext();
        if ($category->hospital_id !== $hospital?->id) {
            abort(403, 'Unauthorized to verify category belonging to another facility.');
        }

        $category->update(['status' => 'Active']);

        return back()->with('success', "Category '{$category->name}' has been verified & approved!");
    }
}
