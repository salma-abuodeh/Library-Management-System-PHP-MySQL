<?php
require 'db.php';

$signupError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = trim($_POST['username']);
    $e = trim($_POST['email']);
    $p = trim($_POST['password']);

    $r = (isset($_POST['role']) && $_POST['role'] === 'staff') ? 'staff' : 'student';

    if ($u === "" || $e === "" || $p === "") {
        $signupError = "Please fill out all fields.";
    } 
    else if (!filter_var($e, FILTER_VALIDATE_EMAIL)) {
        $signupError = "Invalid email format.";
    }
    else if (strlen($p) < 6) {
        $signupError = "Password must be at least 6 characters.";
    }
    else {
        $uEsc = mysqli_real_escape_string($conn, $u);
        $eEsc = mysqli_real_escape_string($conn, $e);

        $check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$uEsc' OR email = '$eEsc'");
        if (mysqli_num_rows($check) > 0) {
            $signupError = "Username or Email already exists.";
        } else {
            $hashed = password_hash($p, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(username,email,password,role)
                    VALUES('$uEsc','$eEsc','$hashed','$r')";
            mysqli_query($conn, $sql);

            header("Location: index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup - Library Management System</title>

    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        * { font-family: "Poppins", sans-serif; }

        body{
            background: url('wallpaper.png') no-repeat center center;
            background-size: cover;
            background-attachment: fixed;

            min-height: 100vh;
            display:flex;
            justify-content:center;
            align-items:center;

            position:relative;
            overflow:hidden;
            padding:40px 20px;
        }

        body::before{
            content:'';
            position:absolute;
            inset:0;
            background: rgba(252,245,238,0.35);
            pointer-events:none;
        }

        .card{
            width:520px;
            height:640px;             
            max-width:100%;

            border-radius:30px;
            padding:45px;

            background:#FCF5EE;
            border:none;

            box-shadow:
                0 25px 70px rgba(133,14,53,0.4),
                0 0 0 1px rgba(255,196,196,0.3);

            animation: fadeIn 0.7s ease;
            position:relative;
            z-index:1;

            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .card::before{
            content:'';
            position:absolute;
            top:0; left:0; right:0;
            height:6px;
            background: linear-gradient(90deg,#EE6983 0%,#850E35 100%);
            border-radius:30px 30px 0 0;
            box-shadow: 0 0 20px rgba(238,105,131,0.5);
        }

        @keyframes fadeIn{
            from { opacity:0; transform: translateY(35px); }
            to   { opacity:1; transform: translateY(0); }
        }

        .card h3{
            color:#850E35;
            font-weight:800;
            font-size:32px;
            margin-bottom:22px;
        }

        label{
            font-weight:800 !important;
            color:#850E35 !important;
            text-transform:uppercase;
            font-size:14px;
            letter-spacing:0.5px;
        }

        .mb-3{
            position: relative;
            margin-bottom: 28px !important; /* space for error when it appears */
        }

        .form-control, .form-select{
            border:2px solid rgba(255,196,196,0.5) !important;
            border-radius:15px !important;
            padding:14px 18px !important;
            color:#850E35 !important;
            font-weight:600 !important;
            transition: all 0.3s ease !important;
        }

        .form-control:focus, .form-select:focus{
            border-color:#EE6983 !important;
            box-shadow:0 0 0 5px rgba(238,105,131,0.15) !important;
            background:#fff !important;
        }

        input.is-invalid, select.is-invalid{
            border:2px solid #850E35 !important;
            background: rgba(255,196,196,0.3) !important;
            box-shadow: 0 0 0 4px rgba(133,14,53,0.1) !important;
        }

        .text-error{
            color:#850E35;
            font-size:13px;
            font-weight:700;

            position:absolute;
            bottom:-18px;
            left:0;

            opacity:0;
            transform: translateY(-4px);
            transition: all 0.25s ease;
            pointer-events:none;
            margin:0;
        }

        .text-error.show{
            opacity:1;
            transform: translateY(0);
        }

        .btn-success{
            background: linear-gradient(135deg,#EE6983 0%,#850E35 100%) !important;
            border:none !important;
            border-radius:30px !important;
            padding:16px !important;
            font-weight:700 !important;
            box-shadow:0 8px 25px rgba(238,105,131,0.4) !important;
            transition: all 0.3s ease !important;
            color:#FCF5EE !important;
        }

        .btn-success:hover{
            background: linear-gradient(135deg,#850E35 0%,#EE6983 100%) !important;
            transform: translateY(-3px) !important;
            box-shadow:0 12px 35px rgba(238,105,131,0.5) !important;
        }

        .alert-danger{
            background: linear-gradient(135deg, rgba(255,196,196,0.6), rgba(238,105,131,0.25)) !important;
            color:#850E35 !important;
            border:2px solid #EE6983 !important;
            border-radius:15px !important;
            font-weight:600 !important;
            margin-bottom:14px;
        }

        .text-center a{
            color:#EE6983 !important;
            font-weight:600;
            transition: all 0.3s ease;
        }
        .text-center a:hover{ color:#850E35 !important; }

        @media (max-width:576px){
            .card{
                height:auto;
                padding:32px;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <h3 class="text-center fw-semibold">Create an Account 🌟</h3>

        <?php if ($signupError): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($signupError) ?></div>
        <?php endif; ?>

        <form id="signupForm" method="POST" novalidate>
            <div class="mb-3">
                <label>Username</label>
                <input id="sUser" name="username" class="form-control form-control-lg" required
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                <div class="text-error" id="sUserErr">Username must be at least 3 characters.</div>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input id="sEmail" name="email" class="form-control form-control-lg" required
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                <div class="text-error" id="sEmailErr">Please enter a valid email.</div>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input id="sPass" name="password" type="password" class="form-control form-control-lg" required>
                <div class="text-error" id="sPassErr">Password must be at least 6 characters.</div>
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select id="sRole" name="role" required class="form-select form-select-lg">
                    <option value="student" <?= (isset($_POST['role']) && $_POST['role']=="student") ? "selected" : "" ?>>Student</option>
                    <option value="staff"   <?= (isset($_POST['role']) && $_POST['role']=="staff")   ? "selected" : "" ?>>Staff</option>
                </select>
                <div class="text-error" id="sRoleErr">Please choose a role.</div>
            </div>

            <button class="btn btn-success w-100 btn-lg mt-2">Signup</button>

            <div class="text-center mt-3">
                <a href="login.php" class="text-decoration-none">Already have an account?</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("signupForm").addEventListener("submit", function(e) {
            let valid = true;

            const u = document.getElementById("sUser");
            const em = document.getElementById("sEmail");
            const p = document.getElementById("sPass");
            const role = document.getElementById("sRole");

            const showErr = (id, show) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.classList.toggle("show", !!show);
            };

            // Username
            if (u.value.trim().length < 3) {
                u.classList.add("is-invalid");
                showErr("sUserErr", true);
                valid = false;
            } else {
                u.classList.remove("is-invalid");
                showErr("sUserErr", false);
            }

            // Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(em.value.trim())) {
                em.classList.add("is-invalid");
                showErr("sEmailErr", true);
                valid = false;
            } else {
                em.classList.remove("is-invalid");
                showErr("sEmailErr", false);
            }

            // Password
            if (p.value.trim().length < 6) {
                p.classList.add("is-invalid");
                showErr("sPassErr", true);
                valid = false;
            } else {
                p.classList.remove("is-invalid");
                showErr("sPassErr", false);
            }

            // Role
            if (!role.value) {
                role.classList.add("is-invalid");
                showErr("sRoleErr", true);
                valid = false;
            } else {
                role.classList.remove("is-invalid");
                showErr("sRoleErr", false);
            }

            if (!valid) e.preventDefault();
        });
    </script>
</body>
</html>
