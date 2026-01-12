<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

/* ---- server-side e-mail check ---- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $city    = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $contact = trim($_POST['contact_info']);

    if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
        die('<div class="alert alert-danger">Please enter a valid e-mail address.</div>');
    }
    $contact = mysqli_real_escape_string($conn, $contact);

    $sql = "
        INSERT INTO publisher(name, city, country, contact_info)
        VALUES('$name', '$city', '$country', '$contact')
    ";
    mysqli_query($conn, $sql);

    header("Location: publishers.php");
    exit;
}
?>

<div style="margin-top:120px;"></div>

<div class="container col-md-6">
    <h2>Add Publisher</h2>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">City</label>
            <input name="city" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Country</label>
            <input name="country" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Info (e-mail)</label>
            <input name="contact_info" type="email" class="form-control" required
                   placeholder="contact@publisher.com">
        </div>

        <button class="btn btn-success">Save</button>
        <a href="publishers.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>