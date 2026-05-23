<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Store a newly created section.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sections,name',
        ]);

        $section = Section::create([
            'name' => $request->name,
            'teacher_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section created successfully!',
            'section' => $section,
        ]);
    }

    /**
     * Delete a section.
     */
    public function destroy(Section $section)
    {
        if ($section->teacher_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this section.',
            ], 403);
        }

        $section->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully!',
        ]);
    }

    /**
     * Get all sections for authenticated teacher.
     */
    public function list()
    {
        $sections = Section::where('teacher_id', Auth::id())
            ->withCount('students')
            ->get();

        return response()->json([
            'success' => true,
            'sections' => $sections,
        ]);
    }
}
