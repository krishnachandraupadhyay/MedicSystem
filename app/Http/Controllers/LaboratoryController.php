<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboratoryController extends Controller
{
    /**
     * Laboratory Admin Dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Enforce laboratory enabled permission check
        $hospital = $user->hospital ?? Hospital::where('email', $user->email)->first() ?? Hospital::where('name', $user->hospital_name)->first();

        if ($hospital && ! $hospital->enable_laboratory) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Laboratory access is currently disabled for this facility. Please contact the Super Admin to enable it in Admin Permissions.',
            ]);
        }

        $stats = [
            'total_tests' => 248,
            'samples_collected' => 182,
            'pending_reports' => 24,
            'completed_reports' => 224,
        ];

        $recentTests = [
            [
                'id' => 'SAMP-101',
                'patient_name' => 'Amit Kumar Verma',
                'test_name' => 'Complete Blood Count (CBC)',
                'department' => 'Hematology',
                'doctor' => 'Dr. Sharma',
                'time' => '10:15 AM',
                'status' => 'Completed',
            ],
            [
                'id' => 'SAMP-102',
                'patient_name' => 'Pooja Rani',
                'test_name' => 'Liver Function Test (LFT)',
                'department' => 'Biochemistry',
                'doctor' => 'Dr. Gupta',
                'time' => '11:00 AM',
                'status' => 'In Process',
            ],
            [
                'id' => 'SAMP-103',
                'patient_name' => 'Mohd. Zeeshan',
                'test_name' => 'Lipid Profile & Glucose (F/PP)',
                'department' => 'Pathology',
                'doctor' => 'Dr. Khan',
                'time' => '11:45 AM',
                'status' => 'Pending Sample',
            ],
            [
                'id' => 'SAMP-104',
                'patient_name' => 'Sunita Devi',
                'test_name' => 'Thyroid Profile (T3, T4, TSH)',
                'department' => 'Endocrinology',
                'doctor' => 'Dr. Mehta',
                'time' => '12:30 PM',
                'status' => 'Completed',
            ],
        ];

        return view('laboratory.dashboard', compact('hospital', 'stats', 'recentTests'));
    }
}
