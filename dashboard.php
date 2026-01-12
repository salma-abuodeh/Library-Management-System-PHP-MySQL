<?php
include 'navbar.php';
?>


<h2>Welcome, <?= $_SESSION['username']; ?>!</h2>
<p class="page-subtitle">
    This is your library management dashboard.
</p>

<div class="dashboard-cards" style="margin-bottom: 80px;">

    <!-- BOOKS -->
    <div class="dash-card blue">
        <h3>Books</h3>
        <a class="small-btn" href="books.php">Manage</a>
    </div>

    <!-- BORROWERS -->
    <div class="dash-card green">
        <h3>Borrowers</h3>
        <a class="small-btn" href="borrowers.php">Manage</a>
    </div>

    <!-- LOANS -->
    <div class="dash-card dark">
        <h3>Loans</h3>
        <a class="small-btn" href="loans.php">Manage</a>
    </div>

    <!-- AUTHORS -->
    <div class="dash-card blue">
        <h3>Authors</h3>
        <a class="small-btn" href="authors.php">Manage</a>
    </div>

    <!-- PUBLISHERS -->
    <div class="dash-card green">
        <h3>Publishers</h3>
        <a class="small-btn" href="publishers.php">Manage</a>
    </div>

    <!-- SALES -->
    <div class="dash-card dark">
        <h3>Sales</h3>
        <a class="small-btn" href="sales.php">Manage</a>
    </div>

</div>