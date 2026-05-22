<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_registration_creates_pending_approval_status(): void
    {
        $response = $this->post(route('teacher.register'), [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('teacher.login'));
        $response->assertSessionHas('success', 'Account created successfully! Please wait for admin approval before logging in.');

        $user = User::where('email', 'john.doe@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('teacher', $user->role);
        $this->assertEquals('pending', $user->approval_status);
    }

    public function test_prevents_pending_teachers_from_logging_in(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'approval_status' => 'pending',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('teacher.login.submit'), [
            'email' => $teacher->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('teacher.login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_allows_approved_teachers_to_log_in(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'approval_status' => 'approved',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('teacher.login.submit'), [
            'email' => $teacher->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $this->assertAuthenticatedAs($teacher);
    }

    public function test_prevents_rejected_teachers_from_logging_in(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'approval_status' => 'rejected',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('teacher.login.submit'), [
            'email' => $teacher->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('teacher.login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_see_pending_teachers_in_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->teacher()->count(3)->create([
            'approval_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertSuccessful();
        $response->assertViewHas('pendingTeachers');
        $this->assertCount(3, $response->viewData('pendingTeachers'));
    }

    public function test_admin_can_approve_a_pending_teacher(): void
    {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create([
            'approval_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.teacher.approve', $teacher->id));

        $response->assertRedirect();
        $teacher->refresh();
        $this->assertEquals('approved', $teacher->approval_status);
    }

    public function test_admin_can_reject_a_pending_teacher(): void
    {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create([
            'approval_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.teacher.reject', $teacher->id));

        $response->assertRedirect();
        $teacher->refresh();
        $this->assertEquals('rejected', $teacher->approval_status);
    }

    public function test_admin_can_reset_teacher_approval_status_to_pending(): void
    {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create([
            'approval_status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.teacher.reset', $teacher->id));

        $response->assertRedirect();
        $teacher->refresh();
        $this->assertEquals('pending', $teacher->approval_status);
    }
}
