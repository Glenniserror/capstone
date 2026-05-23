<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Return all sections as JSON.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $sections = Section::with('students')->orderBy('created_at')->get();

        return response()->json([
            'sections' => $sections->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'students' => $s->students->pluck('id')->toArray(),
                'students_count' => $s->students()->count(),
                'avg_progress' => 0, // Placeholder - will be calculated based on actual performance data
                'needs_attention' => 0, // Placeholder - will be calculated based on performance thresholds
            ]),
        ]);
    }

    /**
     * Create a new section (teacher only).
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sections,name',
        ]);

        $section = Section::create($validated);

        return response()->json([
            'id' => $section->id,
            'name' => $section->name,
            'students' => [],
        ], 201);
    }

    /**
     * Update a section (teacher only).
     */
    public function update(Request $request, Section $section): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sections,name,'.$section->id,
        ]);

        $section->update($validated);

        return response()->json([
            'id' => $section->id,
            'name' => $section->name,
            'students' => $section->students->pluck('id')->toArray(),
        ]);
    }

    /**
     * Delete a section and unassign students (teacher only).
     */
    public function destroy(Section $section): \Illuminate\Http\JsonResponse
    {
        // Unassign students from this section
        $section->users()->update(['section_id' => null]);

        $section->delete();

        return response()->json(['message' => 'Section deleted successfully'], 200);
    }
}
