<?php

namespace App\Http\Controllers;

use App\Models\User;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $pendingStudents = User::where('role', 'student')
            ->where('approval_status', 'pending')
            ->latest()
            ->get();

        return view('dashboard.teacher_dashboard', compact('pendingStudents'));
    }
}
