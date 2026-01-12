<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="icon" type="image/png" href="logo.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css?v=3">

<style>
@media (max-width: 991px) {
    .navbar-collapse {
        background: linear-gradient(135deg, #850E35 0%, #EE6983 100%);
        margin-top: 15px;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(133, 14, 53, 0.4);
    }
    
    .navbar-nav {
        gap: 5px;
    }
    
    .navbar .nav-link {
        margin: 5px 0;
        padding: 12px 20px !important;
    }
    
    .navbar-text {
        margin: 10px 0;
        display: block;
        text-align: center;
    }
    
    .logout-link {
        margin-top: 10px;
        text-align: center;
        display: block;
    }
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Library System</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'books.php') ? 'active' : ''; ?>" href="books.php">Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'authors.php') ? 'active' : ''; ?>" href="authors.php">Authors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'publishers.php') ? 'active' : ''; ?>" href="publishers.php">Publishers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'borrowers.php') ? 'active' : ''; ?>" href="borrowers.php">Borrowers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'loans.php') ? 'active' : ''; ?>" href="loans.php">Loans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'sales.php') ? 'active' : ''; ?>" href="sales.php">Sales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'reports.php') ? 'active' : ''; ?>" href="reports.php">Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'about.php') ? 'active' : ''; ?>" href="about.php">About</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <li class="nav-item me-2 d-flex align-items-center">
                        <span class="navbar-text">
                            👤 <?= htmlspecialchars($_SESSION['username']); ?> (<?= htmlspecialchars($_SESSION['role']); ?>)
                        </span>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link logout-link" href="logout.php">Logout</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'index.php') ? 'active' : ''; ?>" href="index.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'signup.php') ? 'active' : ''; ?>" href="signup.php">Signup</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>