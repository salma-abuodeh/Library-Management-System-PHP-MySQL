<?php
session_start();
require 'db.php';

$loginError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);

    if ($u === "" || $p === "") {
        $loginError = "Please fill out all fields.";
    } else {
        $u = mysqli_real_escape_string($conn, $u);

        $sql = "SELECT * FROM users WHERE username = '$u'";
        $r = mysqli_query($conn, $sql);

        if (mysqli_num_rows($r) == 1) {
            $user = mysqli_fetch_assoc($r);

            if (password_verify($p, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header("Location: dashboard.php");
                exit;
            }
        }
        $loginError = "Invalid username or password.";
    }
}
?>
<link rel="icon" type="image/png" href="logo.png">
<link rel="stylesheet" 
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

* { font-family: "Poppins", sans-serif; }

body {
    background: url('wallpaper.png') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;

    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}


body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 20% 50%, rgba(252, 245, 238, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 196, 196, 0.15) 0%, transparent 50%);
    pointer-events: none;
}

.card {
    width: 450px;
    border-radius: 30px;
    padding: 40px;
    border: none;
    box-shadow: 
        0 25px 70px rgba(133, 14, 53, 0.4),
        0 0 0 1px rgba(255, 196, 196, 0.3);
    animation: fadeIn 0.7s ease;
    background: #FCF5EE;
    position: relative;
    z-index: 1;
}

.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, #EE6983 0%, #850E35 100%);
    border-radius: 30px 30px 0 0;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(35px); }
    to { opacity: 1; transform: translateY(0); }
}

.card h3 {
    color: #850E35;
    font-weight: 800;
    font-size: 32px;
}

input.is-invalid {
    border: 2px solid #850E35;
    background: rgba(255, 196, 196, 0.3);
    box-shadow: 0 0 0 4px rgba(133, 14, 53, 0.1);
}

.text-error {
    color: #850E35;
    font-size: 14px;
    margin-top: 6px;
    font-weight: 700;
}

.form-control {
    border: 2px solid rgba(255, 196, 196, 0.5);
    border-radius: 15px;
    padding: 14px 18px;
    color: #850E35;
    font-weight: 600;
}

.form-control:focus {
    border-color: #EE6983;
    box-shadow: 0 0 0 5px rgba(238, 105, 131, 0.15);
}

.fw-medium {
    
    font-weight: 800;
    color: #850E35;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 0.5px;
}


.btn-primary {
    background: linear-gradient(135deg, #EE6983 0%, #850E35 100%);
    border: none;
    border-radius: 30px;
    padding: 16px;
    font-weight: 700;
    box-shadow: 0 8px 25px rgba(238, 105, 131, 0.4);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #850E35 0%, #EE6983 100%);
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(238, 105, 131, 0.5);
}

.alert-danger {
    background: linear-gradient(135deg, rgba(255, 196, 196, 0.6), rgba(238, 105, 131, 0.25));
    color: #850E35;
    border: 2px solid #EE6983;
    border-radius: 15px;
    font-weight: 600;
}

.text-center a {
    color: #EE6983;
    font-weight: 600;
    transition: all 0.3s ease;
}

.text-center a:hover {
    color: #850E35;
}
.card label {
  font-weight: 800 !important;
}

</style>

<div class="card">
    <h3 class="text-center fw-semibold mb-4">Login</h3>

    <?php if ($loginError): ?>
        <div class="alert alert-danger text-center"><?= $loginError ?></div>
    <?php endif; ?>

    <form id="loginForm" method="POST" novalidate>
        <div class="mb-3">
            <label class="fw-medium">Username</label>
            <input id="loginUser" name="username" type="text" required
                value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                class="form-control form-control-lg">
            <div class="text-error d-none" id="userErr">Please enter your username.</div>
        </div>

        <div class="mb-3">
            <label class="fw-medium">Password</label>
            <input id="loginPass" name="password" type="password" required
                class="form-control form-control-lg">
            <div class="text-error d-none" id="passErr">Please enter your password.</div>
        </div>

        <button class="btn btn-primary w-100 btn-lg">Login</button>

        <div class="text-center mt-3">
            <a href="signup.php" class="text-decoration-none">Create an account</a>
        </div>
    </form>
</div>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e) {
    let valid = true;

    const user = document.getElementById("loginUser");
    const pass = document.getElementById("loginPass");

    if (user.value.trim() === "") {
        user.classList.add("is-invalid");
        document.getElementById("userErr").classList.remove("d-none");
        valid = false;
    } else {
        user.classList.remove("is-invalid");
        document.getElementById("userErr").classList.add("d-none");
    }

    if (pass.value.trim() === "") {
        pass.classList.add("is-invalid");
        document.getElementById("passErr").classList.remove("d-none");
        valid = false;
    } else {
        pass.classList.remove("is-invalid");
        document.getElementById("passErr").classList.add("d-none");
    }

    if (!valid) e.preventDefault();
});
</script>