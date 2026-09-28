<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Kasir - Toko Hikfra Collection</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --primary: #6C5CE7;
            --primary-dark: #5546d1;
            --accent: #00D2A0;
            --accent2: #FF9F43;
            --ink: #1a1b2e;
            --muted: #8a8fa3;
            --bg-panel: #0f0c29;
        }

        *{ box-sizing:border-box; }

        body{
            margin:0;
            min-height:100vh;
            font-family:'Plus Jakarta Sans', sans-serif;
            background:#f4f5fb;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
        }

        /* ===== SHELL ===== */
        .auth-shell{
            width:100%;
            max-width:960px;
            min-height:600px;
            display:flex;
            border-radius:28px;
            overflow:hidden;
            box-shadow:0 30px 80px rgba(76,60,180,.18), 0 6px 20px rgba(0,0,0,.04);
            background:#fff;
            animation:riseIn .6s cubic-bezier(.2,.8,.2,1) both;
        }
        @keyframes riseIn{
            from{ opacity:0; transform:translateY(24px) scale(.98); }
            to{ opacity:1; transform:translateY(0) scale(1); }
        }

        /* ===== LEFT BRAND PANEL ===== */
        .brand-panel{
            position:relative;
            flex:1 1 46%;
            background:
                radial-gradient(120% 120% at 15% 10%, #7b6bf0 0%, transparent 55%),
                radial-gradient(120% 120% at 90% 90%, #00d2a0 0%, transparent 50%),
                linear-gradient(160deg, #241a63 0%, #0f0c29 100%);
            padding:48px 40px;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            color:#fff;
            overflow:hidden;
        }
        .brand-panel .blob{
            position:absolute;
            border-radius:50%;
            filter:blur(2px);
            opacity:.35;
            background:linear-gradient(135deg,#00d2a0,#6c5ce7);
            animation:drift 9s ease-in-out infinite;
        }
        .brand-panel .blob.b1{ width:180px; height:180px; top:-50px; right:-50px; }
        .brand-panel .blob.b2{ width:130px; height:130px; bottom:20px; left:-40px; animation-delay:2s; animation-direction:reverse; }
        @keyframes drift{
            0%,100%{ transform:translateY(0) rotate(0deg); }
            50%{ transform:translateY(-18px) rotate(8deg); }
        }

        .brand-top{ position:relative; z-index:1; }
        .brand-mark{
            width:54px; height:54px;
            border-radius:16px;
            background:rgba(255,255,255,.12);
            backdrop-filter:blur(6px);
            border:1px solid rgba(255,255,255,.18);
            display:flex; align-items:center; justify-content:center;
            font-size:22px;
            margin-bottom:22px;
        }
        .brand-panel h1{
            font-size:26px;
            font-weight:800;
            line-height:1.3;
            margin-bottom:10px;
        }
        .brand-panel p{
            font-size:14px;
            color:rgba(255,255,255,.65);
            line-height:1.6;
            max-width:320px;
        }

        .brand-features{
            position:relative; z-index:1;
            display:flex;
            flex-direction:column;
            gap:14px;
        }
        .brand-features .feat{
            display:flex;
            align-items:center;
            gap:12px;
            font-size:13px;
            color:rgba(255,255,255,.85);
        }
        .brand-features .feat i{
            width:32px; height:32px;
            border-radius:10px;
            background:rgba(255,255,255,.1);
            display:flex; align-items:center; justify-content:center;
            font-size:13px;
            color:#00d2a0;
        }

        .brand-footer{
            position:relative; z-index:1;
            font-size:12px;
            color:rgba(255,255,255,.4);
        }

        /* ===== RIGHT FORM PANEL ===== */
        .form-panel{
            flex:1 1 54%;
            padding:52px 48px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .form-header{ margin-bottom:32px; }
        .form-header h2{
            font-size:24px;
            font-weight:800;
            color:var(--ink);
            margin-bottom:6px;
        }
        .form-header p{
            font-size:13.5px;
            color:var(--muted);
            margin:0;
        }

        .alert-danger{
            border-radius:14px;
            font-size:13px;
            border:none;
            background:#FFF0EF;
            color:#D64545;
            padding:12px 16px;
            display:flex;
            align-items:center;
            margin-bottom:20px;
        }

        .input-wrap{ margin-bottom:18px; }
        .input-wrap label{
            font-size:12.5px;
            font-weight:700;
            color:var(--ink);
            margin-bottom:7px;
            display:block;
        }

        .input-icon-wrap{ position:relative; }
        .input-icon-wrap .input-icon{
            position:absolute;
            left:16px; top:50%;
            transform:translateY(-50%);
            color:#b7bacc;
            font-size:14px;
            transition:.2s;
            pointer-events:none;
        }
        .input-icon-wrap .form-control{
            height:50px;
            border-radius:14px;
            border:1.5px solid #ecedf5;
            background:#f8f9fd;
            padding:0 16px 0 44px;
            font-size:14.5px;
            color:var(--ink);
            transition:.2s;
        }
        .input-icon-wrap .form-control::placeholder{ color:#b7bacc; }
        .input-icon-wrap .form-control:focus{
            border-color:var(--primary);
            background:#fff;
            box-shadow:0 0 0 4px rgba(108,92,231,.12);
            outline:none;
        }
        .input-icon-wrap .form-control:focus ~ .input-icon{ color:var(--primary); }
        .input-icon-wrap .form-control.is-valid{ border-color:var(--accent); }
        .input-icon-wrap .form-control.is-invalid{ border-color:#e74a3b; }

        .toggle-password{
            position:absolute;
            right:16px; top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            color:#b7bacc;
            font-size:14px;
            transition:.2s;
        }
        .toggle-password:hover{ color:var(--primary); }

        .row-between{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:24px;
        }
        .form-check-input{ width:17px; height:17px; margin-top:0; cursor:pointer; }
        .form-check-input:checked{ background-color:var(--primary); border-color:var(--primary); }
        .form-check-label{
            font-size:13px;
            color:var(--muted);
            margin-left:4px;
            cursor:pointer;
        }

        .btn-login{
            width:100%;
            height:52px;
            border-radius:14px;
            font-weight:700;
            font-size:14.5px;
            letter-spacing:.2px;
            background:linear-gradient(135deg, var(--primary), #8a7cf0);
            border:none;
            color:#fff;
            transition:.25s;
            box-shadow:0 10px 24px rgba(108,92,231,.3);
        }
        .btn-login:hover:not(:disabled){
            transform:translateY(-2px);
            box-shadow:0 14px 28px rgba(108,92,231,.38);
            color:#fff;
        }
        .btn-login:disabled{ opacity:.55; cursor:not-allowed; transform:none; }

        .text-danger.small{ font-size:11.5px; margin-top:5px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 860px){
            .auth-shell{ flex-direction:column; max-width:440px; min-height:auto; }
            .brand-panel{ padding:32px 28px; }
            .brand-features{ display:none; }
            .brand-panel h1{ font-size:20px; }
            .brand-panel p{ display:none; }
            .form-panel{ padding:36px 28px 40px; }
        }
    </style>
</head>
<body>

<div class="auth-shell">

    <!-- BRAND / LEFT PANEL -->
    <div class="brand-panel">
        <div class="blob b1"></div>
        <div class="blob b2"></div>

        <div class="brand-top">
            <div class="brand-mark"><i class="fas fa-store"></i></div>
            <h1>Toko Hikfra<br>Collection</h1>
            <p>Sistem kasir &amp; manajemen stok yang cepat, rapi, dan mudah digunakan setiap hari.</p>
        </div>

        <div class="brand-features">
            <div class="feat"><i class="fas fa-bolt"></i> Transaksi kasir super cepat</div>
            <div class="feat"><i class="fas fa-boxes-stacked"></i> Stok barang selalu update</div>
            <div class="feat"><i class="fas fa-chart-line"></i> Laporan penjualan real-time</div>
        </div>

        <div class="brand-footer">&copy; {{ date('Y') }} Toko Hikfra Collection</div>
    </div>

    <!-- FORM / RIGHT PANEL -->
    <div class="form-panel">

        <div class="form-header">
            <h2>Selamat Datang 👋</h2>
            <p>Silakan login untuk melanjutkan ke dashboard kasir.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-circle-exclamation me-2"></i>
                Name/email atau password salah.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- NAME / EMAIL -->
            <div class="input-wrap">
                <label>Name / Email</label>
                <div class="input-icon-wrap">
                    <input type="text"
                           name="login"
                           id="login"
                           value="{{ old('login') }}"
                           class="form-control"
                           placeholder="Name atau Email"
                           required autofocus>
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="input-wrap">
                <label>Password</label>
                <div class="input-icon-wrap">
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Minimal 6 karakter"
                           required>
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <!-- REMEMBER ME -->
            <div class="row-between">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
            </div>

            <button type="submit" class="btn btn-login" id="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>

        </form>

    </div>

</div>

<script>

    /* ===== TOGGLE PASSWORD ===== */
    document.getElementById("togglePassword").addEventListener("click", function(){
        const input = document.getElementById("password");
        const isPassword = input.type === "password";
        input.type = isPassword ? "text" : "password";
        this.classList.toggle("fa-eye", !isPassword);
        this.classList.toggle("fa-eye-slash", isPassword);
    });

    /* ===== VALIDASI FORM ===== */
    document.addEventListener("DOMContentLoaded", function(){

        const loginInput    = document.getElementById("login");
        const passwordInput = document.getElementById("password");
        const loginBtn      = document.getElementById("btn-login");

        const loginError = document.createElement("div");
        loginError.classList.add("text-danger", "small");
        loginInput.parentNode.appendChild(loginError);

        const passwordError = document.createElement("div");
        passwordError.classList.add("text-danger", "small");
        passwordInput.parentNode.parentNode.appendChild(passwordError);

        function validateLogin(){
            if(loginInput.value.length < 3){
                loginInput.classList.add("is-invalid");
                loginInput.classList.remove("is-valid");
                loginError.textContent = "Minimal 3 karakter";
                return false;
            }
            loginInput.classList.remove("is-invalid");
            loginInput.classList.add("is-valid");
            loginError.textContent = "";
            return true;
        }

        function validatePassword(){
            if(passwordInput.value.length < 6){
                passwordInput.classList.add("is-invalid");
                passwordInput.classList.remove("is-valid");
                passwordError.textContent = "Password minimal 6 karakter";
                return false;
            }
            passwordInput.classList.remove("is-invalid");
            passwordInput.classList.add("is-valid");
            passwordError.textContent = "";
            return true;
        }

        function validateForm(){
            loginBtn.disabled = !(validateLogin() && validatePassword());
        }

        loginInput.addEventListener("keyup", validateForm);
        passwordInput.addEventListener("keyup", validateForm);
        loginBtn.disabled = true;

    });

</script>

</body>
</html>