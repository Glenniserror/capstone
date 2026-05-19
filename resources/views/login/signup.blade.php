<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up | Bubog NHS</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        html, body { height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #1E88E5 0%, #80DEEA 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .wrap {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            display: flex;
            flex-direction: row;
            width: 100%;
            max-width: 1100px;
            min-height: 640px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        }

        /* ── LEFT SIDE ── */
        .img-side {
            flex: 0 0 38%;
            background: linear-gradient(160deg, #1565C0 0%, #42A5F5 60%, #80DEEA 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
            padding: 48px 36px;
        }

        .logo-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 26px;
            letter-spacing: -0.5px;
            background: rgba(255, 255, 255, 0.15);
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .logo-circle img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .school-name {
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .side-decor {
            margin-top: 8px;
            display: flex;
            gap: 6px;
        }

        .side-decor span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: rgba(255,255,255,0.35);
        }

        .side-decor span:nth-child(2) {
            background: rgba(255,255,255,0.6);
        }

        /* ── RIGHT SIDE ── */
        .form-side {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 52px;
            background: #fff;
            overflow-y: auto;
        }

        .form-content {
            width: 100%;
            max-width: 420px;
        }

        h2 {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .sub {
            font-size: 13px;
            color: #888;
            margin-bottom: 22px;
        }

        .back-link-outside {
            display: inline-block;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            margin-bottom: 22px;
            font-weight: 500;
            transition: 0.2s;
        }

        .back-link-outside:hover {
            color: #fff;
            transform: translateX(-2px);
        }

        @media (max-width: 700px) {
            .back-link-outside { font-size: 12px; margin-bottom: 12px; }
        }

        /* Role tabs */
        .role-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }

        .role-tab {
            flex: 1;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            border-radius: 10px;
            padding: 6px 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            font-size: 12px;
            font-weight: 600;
            color: #888;
            transition: 0.2s;
            cursor: pointer;
        }

        .role-tab i {
            font-size: 18px;
        }

        .role-tab.active {
            border-color: #1E88E5;
            color: #1E88E5;
            background: #EBF5FF;
        }

        /* Google btn */
        .google-btn {
            width: 100%;
            padding: 13px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            transition: 0.2s;
            margin-bottom: 2px;
            cursor: pointer;
        }

        .google-btn:hover {
            background: #f9fafb;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 14px 0;
            font-size: 12px;
            color: #bbb;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* Two-column row */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px;
        }

        /* Input groups */
        .ig {
            margin-bottom: 12px;
        }

        .ig label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }

        .ig .iw {
            position: relative;
            display: flex;
            align-items: center;
        }

        .ig .iw i.ico {
            position: absolute;
            left: 12px;
            font-size: 15px;
            color: #aaa;
            pointer-events: none;
        }

        .ig input {
            width: 100%;
            padding: 11px 13px 11px 38px;
            background: #F1F5F9;
            border: 1.5px solid transparent;
            border-radius: 10px;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: 0.2s;
        }

        .ig input:focus {
            background: #fff;
            border-color: #1E88E5;
        }

        .eye {
            position: absolute;
            right: 12px;
            color: #aaa;
            font-size: 16px;
            cursor: pointer;
        }

        /* Section field — hidden when not student */
        #section-row { transition: 0.2s; }

        /* Password strength */
        .pw-strength {
            margin-top: 5px;
        }

        .pw-strength-bar {
            height: 3px;
            border-radius: 2px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .pw-strength-fill {
            height: 100%;
            border-radius: 2px;
            width: 0%;
            transition: width 0.4s ease, background 0.4s ease;
        }

        .pw-strength-label {
            font-size: 10px;
            margin-top: 3px;
            color: #9ca3af;
        }

        /* Sign Up button */
        .btn-main {
            width: 100%;
            padding: 15px;
            background: #1E88E5;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.2s;
            cursor: pointer;
            margin-top: 4px;
        }

        .btn-main:hover {
            background: #1565C0;
        }

        .terms {
            font-size: 11px;
            color: #aaa;
            text-align: center;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .terms a {
            color: #1E88E5;
            text-decoration: none;
        }

        .terms a:hover { text-decoration: underline; }

        .signin-link {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 14px;
        }

        .signin-link a {
            color: #1E88E5;
            font-weight: 600;
            text-decoration: none;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }

        /* Mobile */
        @media (max-width: 700px) {
            body { padding: 12px; }

            .card {
                flex-direction: column;
                min-height: auto;
                border-radius: 16px;
            }

            .img-side {
                flex: 0 0 auto;
                padding: 24px 20px 20px;
                gap: 12px;
            }

            .logo-circle {
                width: 72px;
                height: 72px;
            }

            .school-name { font-size: 9px; }
            .side-decor span { width: 5px; height: 5px; }

            .form-side { padding: 24px 18px; overflow-y: auto; }
            .form-content { max-width: 100%; }

            h2 { font-size: 16px; margin-bottom: 2px; }
            .sub { font-size: 10px; margin-bottom: 12px; }

            .role-tabs { gap: 5px; margin-bottom: 10px; }
            .role-tab { padding: 7px 3px; font-size: 9px; border-radius: 7px; }
            .role-tab i { font-size: 14px; }

            .google-btn { padding: 9px; font-size: 11px; gap: 5px; }
            .divider { font-size: 10px; margin: 8px 0; }

            .two-col { grid-template-columns: 1fr; gap: 0; }

            .ig { margin-bottom: 8px; }
            .ig label { font-size: 10px; margin-bottom: 3px; }
            .ig input { padding: 8px 9px 8px 30px; font-size: 11px; border-radius: 8px; }
            .ig .iw i.ico { left: 9px; font-size: 12px; }
            .eye { font-size: 12px; right: 8px; }

            .terms { font-size: 9px; }
            .btn-main { padding: 11px; font-size: 13px; border-radius: 8px; }
            .signin-link { font-size: 10px; margin-top: 8px; }
        }
    </style>
</head>
<body>

<div class="wrap">
  <div>
    <a href="{{ route('homepage') }}" class="back-link-outside">← Back to Homepage</a>

    <div class="card">

      <!-- LEFT: branding side -->
      <div class="img-side">
        <div class="logo-circle">
  <img src="{{ asset('image/587572187-777024998723535-6772324307557000990-n-fotor-20260519155328.png') }}" alt="Bubog NHS Logo">
</div>

        <div class="school-name">Bubog National High School</div>

        <div class="side-decor">
          <span></span><span></span><span></span>
        </div>
      </div>

      <!-- RIGHT: form side -->
      <div class="form-side">
        <div class="form-content">
          <h2>Create account</h2>
          <p class="sub" id="sub-text">Sign up as a student for free</p>

          <!-- Role tabs -->
          <div class="role-tabs">
            <button class="role-tab active" onclick="setRole('student', this)">
              <i class="fa-solid fa-user-graduate"></i>
              <span>Student</span>
            </button>
            <button class="role-tab" onclick="setRole('teacher', this)">
              <i class="fa-solid fa-chalkboard-user"></i>
              <span>Teacher</span>
            </button>
            <button class="role-tab" onclick="setRole('admin', this)">
              <i class="fa-solid fa-lock"></i>
              <span>Admin</span>
            </button>
          </div>

          <!-- Google -->
          <button class="google-btn">
            <svg width="17" height="17" viewBox="0 0 48 48">
              <path fill="#FFC107" d="M43.6 20H24v8h11.1C33.5 33.2 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C33.9 6.5 29.2 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20c11 0 19.7-8 19.7-20 0-1.3-.1-2.7-.4-4z"/>
              <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.5 15.1 18.9 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C33.9 6.5 29.2 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/>
              <path fill="#4CAF50" d="M24 44c5.2 0 9.8-1.8 13.4-4.7l-6.2-5.2C29.4 35.6 26.8 36 24 36c-5.3 0-9.5-2.8-11.1-7H6.3C9.7 39.7 16.3 44 24 44z"/>
              <path fill="#1976D2" d="M43.6 20H24v8h11.1c-.8 2.3-2.3 4.2-4.3 5.5l6.2 5.2C40.5 35.5 44 30.2 44 24c0-1.3-.1-2.7-.4-4z"/>
            </svg>
            Continue with Google
          </button>

          <div class="divider">or sign up with email</div>

          <!-- First & Last name -->
          <div class="two-col">
            <div class="ig">
              <label>First name</label>
              <div class="iw">
                <i class="fa-regular fa-user ico"></i>
                <input type="text" id="fname" placeholder="Juan" autocomplete="given-name">
              </div>
            </div>
            <div class="ig">
              <label>Last name</label>
              <div class="iw">
                <i class="fa-regular fa-user ico"></i>
                <input type="text" id="lname" placeholder="dela Cruz" autocomplete="family-name">
              </div>
            </div>
          </div>

          <!-- Email -->
          <div class="ig">
            <label>Email address</label>
            <div class="iw">
              <i class="fa-solid fa-envelope ico"></i>
              <input type="email" id="email" placeholder="Enter your email" autocomplete="email">
            </div>
          </div>

          <!-- Section (student only) -->
          <div class="ig" id="section-row">
            <label id="section-label">Section</label>
            <div class="iw">
              <i class="fa-solid fa-school ico"></i>
              <input type="text" id="section" placeholder="e.g. Einstein">
            </div>
          </div>

          <!-- Password -->
          <div class="ig">
            <label>Password</label>
            <div class="iw">
              <i class="fa-solid fa-lock ico"></i>
              <input type="password" id="pw" placeholder="Create a strong password" oninput="checkStrength()">
              <i class="fa-solid fa-eye eye" onclick="togglePw('pw', this)"></i>
            </div>
            <div class="pw-strength">
              <div class="pw-strength-bar"><div class="pw-strength-fill" id="strength-fill"></div></div>
              <div class="pw-strength-label" id="strength-label">At least 8 characters</div>
            </div>
          </div>

          <!-- Confirm Password -->
          <div class="ig">
            <label>Confirm password</label>
            <div class="iw">
              <i class="fa-solid fa-lock ico"></i>
              <input type="password" id="pw2" placeholder="Repeat your password">
              <i class="fa-solid fa-eye eye" onclick="togglePw('pw2', this)"></i>
            </div>
          </div>

          <!-- Terms -->
          <p class="terms">
            By creating an account you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
          </p>

          <!-- Submit -->
          <button class="btn-main" id="signup-btn" onclick="handleSignUp()">
            <i class="fa-solid fa-user-plus"></i> Create Account
          </button>

          <p class="signin-link">Already have an account? <a href="{{ route('signin-signin') }}">Sign in</a></p>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
/* ---------- Role tabs ---------- */
const roleLabels = {
  student: 'Sign up as a student for free',
  teacher: 'Sign up as a teacher',
  admin:   'Sign up as an administrator'
};

let currentRole = 'student';

function setRole(role, el) {
  currentRole = role;
  document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('sub-text').textContent = roleLabels[role];

  // Show/hide section field for students only
  document.getElementById('section-row').style.display = role === 'student' ? 'block' : 'none';
}

/* ---------- Password toggle ---------- */
function togglePw(id, icon) {
  const p = document.getElementById(id);
  p.type = p.type === 'password' ? 'text' : 'password';
  icon.className = p.type === 'password'
    ? 'fa-solid fa-eye eye'
    : 'fa-solid fa-eye-slash eye';
}

/* ---------- Password strength ---------- */
function checkStrength() {
  const pw = document.getElementById('pw').value;
  const fill = document.getElementById('strength-fill');
  const label = document.getElementById('strength-label');
  let score = 0;
  if (pw.length >= 8) score++;
  if (/[A-Z]/.test(pw)) score++;
  if (/[0-9]/.test(pw)) score++;
  if (/[^A-Za-z0-9]/.test(pw)) score++;
  const map = [
    { w: '0%',   bg: '#e5e7eb', txt: 'At least 8 characters' },
    { w: '25%',  bg: '#ef4444', txt: 'Weak' },
    { w: '50%',  bg: '#f59e0b', txt: 'Fair' },
    { w: '75%',  bg: '#3b82f6', txt: 'Good' },
    { w: '100%', bg: '#22c55e', txt: 'Strong' },
  ];
  fill.style.width = map[score].w;
  fill.style.background = map[score].bg;
  label.textContent = map[score].txt;
  label.style.color = map[score].bg;
}

/* ---------- Sign up ---------- */
function handleSignUp() {
  const fname = document.getElementById('fname').value.trim();
  const lname = document.getElementById('lname').value.trim();
  const email = document.getElementById('email').value.trim();
  const pw    = document.getElementById('pw').value;
  const pw2   = document.getElementById('pw2').value;

  if (!fname || !lname || !email || !pw || !pw2) {
    alert('Please fill in all fields.');
    return;
  }
  if (!email.includes('@')) {
    alert('Enter a valid email address.');
    return;
  }
  if (pw.length < 8) {
    alert('Password must be at least 8 characters.');
    return;
  }
  if (pw !== pw2) {
    alert('Passwords do not match.');
    return;
  }

  console.log('Signing up as:', currentRole, '| name:', fname, lname, '| email:', email);
  // Connect to your Laravel route here
}
</script>

</body>
</html>