<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In | Bubog NHS</title>

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

        /* Logo circle – clickable for image upload */
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
            transition: border-color 0.2s, box-shadow 0.2s;
            flex-shrink: 0;
        }

        .logo-circle:hover {
            border-color: rgba(255,255,255,0.9);
            box-shadow: 0 0 0 4px rgba(255,255,255,0.2);
        }

        .logo-circle img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Camera overlay shown on hover */
        .logo-circle .cam-overlay {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(0,0,0,0.35);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .logo-circle:hover .cam-overlay {
            opacity: 1;
        }

        .cam-overlay i {
            font-size: 22px;
            color: #fff;
        }

        .cam-overlay span {
            font-size: 10px;
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Hidden real file input */
        #logo-upload {
            display: none;
        }

        .school-name {
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.5px;
        }

        /* decorative dots */
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
            padding: 52px 52px;
            background: #fff;
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
            margin-bottom: 22px;
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

        /* Input groups */
        .ig {
            margin-bottom: 14px;
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
            padding: 13px 13px 13px 38px;
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
        }

        /* Row meta */
        .row-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .row-meta label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #555;
        }

        .row-meta input[type="checkbox"] {
            accent-color: #1E88E5;
            width: 14px;
            height: 14px;
        }

        .forgot {
            font-size: 12px;
            color: #1E88E5;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot:hover {
            text-decoration: underline;
        }

        /* Sign In button */
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
        }

        .btn-main:hover {
            background: #1565C0;
        }

        .signup-link {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 14px;
        }

        .signup-link a {
            color: #1E88E5;
            font-weight: 600;
            text-decoration: none;
        }

        .signup-link a:hover {
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

            .form-side { padding: 24px 18px; }

            .form-content { max-width: 100%; }

            h2 { font-size: 16px; margin-bottom: 2px; }

            .sub { font-size: 10px; margin-bottom: 12px; }

            .role-tabs { gap: 5px; margin-bottom: 12px; }

            .role-tab {
                padding: 7px 3px;
                font-size: 9px;
                border-radius: 7px;
            }

            .role-tab i { font-size: 14px; }

            .google-btn { padding: 9px; font-size: 11px; gap: 5px; }

            .divider { font-size: 10px; margin: 8px 0; }

            .ig { margin-bottom: 9px; }

            .ig label { font-size: 10px; margin-bottom: 3px; }

            .ig input {
                padding: 9px 9px 9px 30px;
                font-size: 11px;
                border-radius: 8px;
            }

            .ig .iw i.ico { left: 9px; font-size: 12px; }

            .eye { font-size: 12px; right: 8px; }

            .row-meta { margin-bottom: 10px; }

            .row-meta label { font-size: 9px; gap: 3px; }

            .forgot { font-size: 9px; }

            .btn-main { padding: 11px; font-size: 13px; border-radius: 8px; }

            .signup-link { font-size: 10px; margin-top: 8px; }
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

        <!-- School Logo -->
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
          <h2>Welcome back</h2>
          <p class="sub" id="sub-text">Sign in to your student account</p>

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

          <div class="divider">or sign in with email</div>

          <!-- Email -->
          <div class="ig">
            <label>Email address</label>
            <div class="iw">
              <i class="fa-solid fa-envelope ico"></i>
              <input type="email" id="email" placeholder="Enter your email" autocomplete="off" name="email">
            </div>
          </div>

          <!-- Password -->
          <div class="ig">
            <label>Password</label>
            <div class="iw">
              <i class="fa-solid fa-lock ico"></i>
              <input type="password" id="pw" placeholder="••••••••" autocomplete="off" name="password">
              <i class="fa-solid fa-eye eye" onclick="togglePw()"></i>
            </div>
          </div>

          <!-- Remember / Forgot -->
          <div class="row-meta">
            <label><input type="checkbox" name="remember"> Remember me for 30 days</label>
            <a href="#" class="forgot">Forgot password?</a>
          </div>

          <!-- Submit -->
          <button class="btn-main" onclick="handleSignIn()">
            <i class="fa-solid fa-right-to-bracket"></i> Sign In
          </button>

          <p class="signup-link">Don't have an account? <a href="{{ route('signin-signup') }}">Sign up free</a></p>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
/* ---------- Role tabs ---------- */
const roleLabels = {
  student: 'Sign in to your student account',
  teacher: 'Sign in to your teacher account',
  admin:   'Sign in to your admin account'
};

let currentRole = 'student';

function setRole(role, el) {
  currentRole = role;
  document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('sub-text').textContent = roleLabels[role];
}

/* ---------- Password toggle ---------- */
function togglePw() {
  const p = document.getElementById('pw');
  const icon = document.querySelector('.eye');
  p.type = p.type === 'password' ? 'text' : 'password';
  icon.className = p.type === 'password'
    ? 'fa-solid fa-eye eye'
    : 'fa-solid fa-eye-slash eye';
}

/* ---------- Sign in ---------- */
function handleSignIn() {
  const email    = document.getElementById('email').value;
  const password = document.getElementById('pw').value;

  if (!email || !password) {
    alert('Please enter email and password');
    return;
  }

  console.log('Signing in as:', currentRole, 'with email:', email);
}


</script>

</body>
</html>