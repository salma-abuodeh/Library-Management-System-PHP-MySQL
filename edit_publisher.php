<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = mysqli_query($conn, "SELECT * FROM publisher WHERE publisher_id = $id");
$pub = mysqli_fetch_assoc($res);

if (!$pub) {
    die("Publisher not found.");
}

/* ---------- server-side e-mail check ---------- */
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
        UPDATE publisher
        SET name = '$name',
            city = '$city',
            country = '$country',
            contact_info = '$contact'
        WHERE publisher_id = $id
    ";
    mysqli_query($conn, $sql);

    header("Location: publishers.php");
    exit;
}
?>

<div style="margin-top:120px;"></div>

<div class="container col-md-6">
    <h2>Edit Publisher</h2>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" class="form-control"
                   value="<?= htmlspecialchars($pub['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">City</label>
            <input name="city" class="form-control"
                   value="<?= htmlspecialchars($pub['city']); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Country</label>
            <input name="country" class="form-control"
                   value="<?= htmlspecialchars($pub['country']); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Info (e-mail)</label>
            <input name="contact_info" type="email" class="form-control" required
                   value="<?= htmlspecialchars($pub['contact_info']); ?>"
                   placeholder="contact@publisher.com">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="publishers.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>