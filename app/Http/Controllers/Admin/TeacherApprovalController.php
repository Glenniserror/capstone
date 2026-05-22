<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class TeacherApprovalController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $pendingTeachers = User::where('role', 'teacher')
            ->where('approval_status', 'pending')
            ->latest()
            ->paginate(15);

        $approvedTeachers = User::where('role', 'teacher')
            ->where('approval_status', 'approved')
            ->latest()
            ->paginate(15);

        $rejectedTeachers = User::where('role', 'teacher')
            ->where('approval_status', 'rejected')
            ->latest()
            ->paginate(15);

        return view('admin.teacher-approvals.index', compact('pendingTeachers', 'approvedTeachers', 'rejectedTeachers'));
    }

    public function approve(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->role !== 'teacher') {
            return back()->withErrors(['error' => 'Invalid user role.']);
        }

        $user->update(['approval_status' => 'approved']);

        return back()->with('success', "{$user->name} has been approved and can now log in.");
    }

    public function reject(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->role !== 'teacher') {
            return back()->withErrors(['error' => 'Invalid user role.']);
        }

        $user->update(['approval_status' => 'rejected']);

        return back()->with('success', "{$user->name} has been rejected.");
    }

    public function reset(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->role !== 'teacher') {
            return back()->withErrors(['error' => 'Invalid user role.']);
        }

        $user->update(['approval_status' => 'pending']);

        return back()->with('success', "{$user->name} status has been reset to pending.");
    }
}
