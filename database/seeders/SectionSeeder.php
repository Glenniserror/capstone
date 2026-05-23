<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default teacher first if none exist
        $teacher = User::where('role', 'teacher')->where('approval_status', 'approved')->first();

        if (! $teacher) {
            $teacher = User::factory()->teacher()->approved()->create([
                'email' => 'teacher@example.com',
                'name' => 'Default Teacher',
            ]);
        }

        // Create default sections
        $sectionNames = [
            'Einstein',
            'Newton',
            'Curie',
            'Darwin',
            'Galileo',
        ];

        foreach ($sectionNames as $name) {
            Section::firstOrCreate(
                ['name' => $name],
                ['teacher_id' => $teacher->id]
            );
        }
    }
}
