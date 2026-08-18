<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Get all students enrolled in the authenticated teacher's sections.
     */
    public function getTeacherStudents()
    {
        $teacher = Auth::user();

        if (!$teacher || $teacher->role !== 'teacher') {
            return response()->json(['students' => []]);
        }

        // Get all students who have a section_id that belongs to this teacher
        $students = User::where('role', 'student')
            ->whereHas('section', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->with('section:id,name')
            ->get(['id', 'name', 'email', 'section_id', 'approval_status', 'created_at', 'updated_at']);

        // Transform data for frontend
        $studentData = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'section_id' => $student->section_id,
                'status' => $this->getStudentStatus($student),
                'progress' => rand(0, 100), // TODO: Calculate real progress from modules
                'lastActive' => $student->updated_at->diffForHumans(),
            ];
        });

        return response()->json(['students' => $studentData]);
    }

    /**
     * Determine student status based on approval and progress.
     */
    private function getStudentStatus($student)
    {
        if ($student->approval_status !== 'approved') {
            return 'Pending';
        }

        $progress = rand(0, 100); // TODO: Calculate real progress
        if ($progress >= 80) return 'Excellent';
        if ($progress >= 60) return 'Good';
        if ($progress >= 40) return 'Average';
        return 'Needs Help';
    }
}
