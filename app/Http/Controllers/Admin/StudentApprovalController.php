<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;

class StudentApprovalController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $pendingStudents = User::where('role', 'student')
            ->where('approval_status', 'pending')
            ->latest()
            ->paginate(15);

        $approvedStudents = User::where('role', 'student')
            ->where('approval_status', 'approved')
            ->latest()
            ->paginate(15);

        $rejectedStudents = User::where('role', 'student')
            ->where('approval_status', 'rejected')
            ->latest()
            ->paginate(15);

        return view('teacher.student-approvals.index', compact('pendingStudents', 'approvedStudents', 'rejectedStudents'));
    }

    public function approve(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->role !== 'student') {
            return back()->withErrors(['error' => 'Invalid user role.']);
        }

        $user->update(['approval_status' => 'approved']);

        return back()->with('success', "{$user->name} has been approved and can now log in.");
    }

    public function reject(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->role !== 'student') {
            return back()->withErrors(['error' => 'Invalid user role.']);
        }

        $user->update(['approval_status' => 'rejected']);

        return back()->with('success', "{$user->name} has been rejected.");
    }

    public function reset(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->role !== 'student') {
            return back()->withErrors(['error' => 'Invalid user role.']);
        }

        $user->update(['approval_status' => 'pending']);

        return back()->with('success', "{$user->name} status has been reset to pending.");
    }
}
