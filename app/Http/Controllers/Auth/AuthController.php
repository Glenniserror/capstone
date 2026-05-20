<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ============ STUDENT ============
    public function showStudentLoginForm()
    {
        return view('login.signin', ['portalType' => 'student']);
    }

    public function studentLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->role !== 'student') {
                Auth::logout();

                return back()->withErrors([
                    'email' => "This account is registered as a {$user->role}. Please use the {$user->role} login portal.",
                ]);
            }

            $request->session()->regenerate();

            return redirect()->route('student.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    public function studentRegister(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $fullName = $validated['firstName'].' '.$validated['lastName'];

        User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        // Do NOT auto-login
        return redirect()->route('student.login')
            ->with('success', 'Account created successfully! Please log in.');
    }

    // ============ TEACHER ============
    public function showTeacherLoginForm()
    {
        return view('login.signin', ['portalType' => 'teacher']);
    }

    public function teacherLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->role !== 'teacher') {
                Auth::logout();

                return back()->withErrors([
                    'email' => "This account is registered as a {$user->role}. Please use the {$user->role} login portal.",
                ]);
            }

            $request->session()->regenerate();

            return redirect()->route('teacher.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    public function teacherRegister(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $fullName = $validated['firstName'].' '.$validated['lastName'];

        User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',
        ]);

        return redirect()->route('teacher.login')
            ->with('success', 'Account created successfully! Please log in.');
    }

    // ============ ADMIN ============
    public function showAdminLoginForm()
    {
        return view('login.signin', ['portalType' => 'admin']);
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                Auth::logout();

                return back()->withErrors([
                    'email' => "This account is registered as a {$user->role}. Please use the {$user->role} login portal.",
                ]);
            }

            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    public function adminRegister(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $fullName = $validated['firstName'].' '.$validated['lastName'];

        User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Account created successfully! Please log in.');
    }

    // ============ LOGOUT ============
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('homepage');
    }

    // ============ GOOGLE OAUTH ============
    public function redirectToGoogle(string $role)
    {
        // Validate role
        if (! in_array($role, ['student', 'teacher', 'admin'])) {
            return redirect()->route('homepage')->withErrors(['error' => 'Invalid role.']);
        }

        // Store the role in session for later use
        session(['oauth_role' => $role]);

        // Check if using mock mode for testing
        if (config('services.google.mode') === 'mock') {
            return $this->mockGoogleLogin();
        }

        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('student.login')->withErrors(['error' => 'Failed to authenticate with Google.']);
        }

        if (! $googleUser) {
            return redirect()->route('student.login')->withErrors(['error' => 'Failed to retrieve Google user data.']);
        }

        // Get the stored role from session
        $role = session('oauth_role') ?? 'student';

        // Check if user exists
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user !== null) {
            // User exists, check if role matches
            if ($user->role !== $role) {
                return redirect()->route($role.'.login')
                    ->withErrors(['email' => "This account is registered as a {$user->role}. Please use the {$user->role} login portal."]);
            }
        } else {
            // Create new user with Google info
            $user = User::create([
                'name' => $googleUser->getName() ?? 'Google User',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(uniqid()),
                'role' => $role,
            ]);
        }

        if ($user instanceof User) {
            // Log the user in
            Auth::login($user);

            // Clear session only after successful authentication
            session()->forget('oauth_role');

            // Redirect to appropriate dashboard
            return redirect()->route($role.'.dashboard');
        }

        return redirect()->route('student.login')->withErrors(['error' => 'Failed to create or retrieve user account.']);
    }

    // Mock Google Login for Testing
    private function mockGoogleLogin()
    {
        $testEmails = [
            'student' => 'test.student@gmail.com',
            'teacher' => 'test.teacher@gmail.com',
            'admin' => 'test.admin@gmail.com',
        ];

        $role = session('oauth_role') ?? 'student';
        $email = $testEmails[$role] ?? 'test.user@gmail.com';

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user !== null) {
            // User exists, check if role matches
            if ($user->role !== $role) {
                return redirect()->route($role.'.login')
                    ->withErrors(['email' => "This account is registered as a {$user->role}. Please use the {$user->role} login portal."]);
            }
        } else {
            // Create new test user
            $user = User::create([
                'name' => ucfirst($role).' Test User',
                'email' => $email,
                'password' => Hash::make('test12345'),
                'role' => $role,
            ]);
        }

        // Log the user in
        if ($user instanceof User) {
            Auth::login($user);

            // Clear session only after successful authentication
            session()->forget('oauth_role');

            // Show success message
            session(['google_auth_success' => true, 'google_auth_email' => $email]);

            // Redirect to appropriate dashboard
            return redirect()->route($role.'.dashboard');
        }

        return redirect()->route('student.login')->withErrors(['error' => 'Failed to create test user.']);
    }
}
