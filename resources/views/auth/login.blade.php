
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In | {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/feather.css') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            background-color: #0d1117 !important;
            color: #e6edf3;
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        .preloader {
            background-color: #0d1117 !important;
        }

        /* ── Layout ── */
        .login-row {
            display: flex;
            min-height: 100vh;
        }

        /* ── Left side: Form ── */
        .login-form-col {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #0d1117;
            position: relative;
            z-index: 2;
        }

        .login-card {
            max-width: 420px;
            width: 100%;
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 48px;
        }

        .login-brand i {
            font-size: 32px;
        }

        .login-brand span {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 3px;
        }

        .login-title {
            font-size: 36px;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 14px;
            color: #8b949e;
            margin-bottom: 32px;
        }

        /* ── Form inputs ── */
        .dark-form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .dark-form-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6e7681;
            font-size: 16px;
            z-index: 2;
        }

        .dark-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: #161b22;
            border: 1px solid #2a3444;
            border-radius: 12px;
            color: #e6edf3;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        .dark-input::placeholder {
            color: #6e7681;
        }

        .dark-input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
            background: #1c2333;
        }

        .dark-input.is-invalid {
            border-color: #f85149;
        }

        .invalid-feedback {
            color: #f85149;
            font-size: 12px;
            margin-top: 6px;
        }

        /* ── Submit button ── */
        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #a78bfa, #7c3aed);
            box-shadow: 0 4px 24px rgba(139, 92, 246, 0.35);
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: #8b949e;
        }

        .register-link a {
            color: #8b5cf6;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .register-link a:hover {
            color: #a78bfa;
            text-decoration: underline;
        }

        /* ── Right side: Image ── */
        .login-image-col {
            width: 50%;
            position: relative;
            overflow: hidden;
        }

        .login-image-col::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(13, 17, 23, 0.7) 0%, rgba(13, 17, 23, 0.3) 50%, rgba(139, 92, 246, 0.1) 100%);
            z-index: 1;
        }

        .login-image-col::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 80px;
            background: linear-gradient(to right, #0d1117, transparent);
            z-index: 2;
        }

        .login-bg-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-overlay-text {
            position: absolute;
            bottom: 60px;
            left: 40px;
            right: 40px;
            z-index: 3;
            color: #fff;
        }

        .login-overlay-text h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.5);
        }

        .login-overlay-text p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            text-shadow: 0 1px 8px rgba(0, 0, 0, 0.5);
        }

        /* ── Register modal ── */
        .modal-content {
            background: #161b22 !important;
            border: 1px solid #2a3444 !important;
            border-radius: 16px !important;
        }

        .modal-body h2 {
            color: #fff !important;
        }

        .modal .dark-input {
            padding-left: 48px;
        }

        .modal .btn-close {
            filter: invert(1);
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .login-row {
                flex-direction: column;
            }
            .login-form-col, .login-image-col {
                width: 100%;
            }
            .login-image-col {
                height: 200px;
                order: -1;
            }
            .login-overlay-text {
                bottom: 20px;
                left: 20px;
                right: 20px;
            }
            .login-overlay-text h3 {
                font-size: 18px;
            }
        }

        /* ── Nav override ── */
        .nav-header {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }
    </style>
</head>

<body>

    <div class="preloader"></div>

    <div class="main-wrap">

        <div class="nav-header bg-transparent shadow-none border-0">
            <div class="nav-top w-100 d-flex justify-content-end">
                <a href="{{ route('home') }}" class="me-auto">
                    <i class="feather-zap text-success display1-size me-2 ms-0"></i>
                    <span class="d-inline-block fredoka-font ls-3 fw-600 text-current font-xxl logo-text mb-0">NEXUS.</span>
                </a>
                <a href="#" class="header-btn d-none d-lg-block fw-500 text-white font-xsss p-3 ms-2 w100 text-center lh-20 rounded-xl" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);" data-bs-toggle="modal" data-bs-target="#Modalregister">Register</a>
            </div>
        </div>

        <div class="login-row">
            <div class="login-form-col">
                <div class="login-card">
                    <h1 class="login-title">Welcome back<br>to Nexus</h1>
                    <p class="login-subtitle">Sign in to connect with your gaming friends.</p>

                    <form method="POST" action="{{ route('login') }}">
                    @csrf

                        {{-- Email --}}
                        <div class="dark-form-group">
                            <i class="ti-email"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Your Email Address" class="dark-input @error('email') is-invalid @enderror">
                            @error('email')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="dark-form-group">
                            <i class="ti-lock"></i>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password" class="dark-input @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="login-btn">Sign In</button>
                    </form>

                    <div class="register-link">
                        Don't have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#Modalregister">Create one</a>
                    </div>
                </div>
            </div>
            <div class="login-image-col d-none d-xl-block">
                <img src="{{ asset('images/gaming/login-dark-bg.png') }}" alt="Gaming Setup" class="login-bg-img">
                <div class="login-overlay-text">
                    <h3>🎮 Find Your Squad</h3>
                    <p>Connect with gamers who share your passion. Team up, compete, and level up together.</p>
                </div>
            </div>
        </div>
    </div>


<!-- Register Modal -->
<div class="modal fade" id="Modalregister" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-xl">

            <button type="button"
                    class="btn-close ms-auto me-3 mt-3"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>

            <div class="modal-body p-4">

                <h2 class="fw-700 mb-4 text-center">
                    Create your account
                </h2>

                <form method="POST" action="{{ route('register') }}" id="register_client">
                    @csrf

                    {{-- Username --}}
                    <div class="dark-form-group">
                        <i class="ti-user"></i>
                        <input type="text" name="username" value="{{ old('username') }}" class="dark-input" placeholder="Username" required>
                    </div>

                    {{-- Name --}}
                    <div class="dark-form-group">
                        <i class="ti-user"></i>
                        <input type="text" name="name" value="{{ old('name') }}" class="dark-input" placeholder="Full Name" required>
                    </div>

                    {{-- Email --}}
                    <div class="dark-form-group">
                        <i class="ti-email"></i>
                        <input type="email" name="email" value="{{ old('email') }}" class="dark-input" placeholder="Email Address" required>
                    </div>

                    {{-- Password --}}
                    <div class="dark-form-group">
                        <i class="ti-lock"></i>
                        <input type="password" name="password" class="dark-input" placeholder="Password" required>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="dark-form-group">
                        <i class="ti-lock"></i>
                        <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" class="dark-input @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation')
                            <span class="invalid-feedback d-block font-xsss">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="login-btn">
                        Create Account
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/plugin.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>