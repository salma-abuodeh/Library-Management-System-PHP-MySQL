<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$res = mysqli_query($conn, "SELECT * FROM author WHERE author_id = $id");
$author = mysqli_fetch_assoc($res);
if (!$author) {
    die("Author not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first   = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last    = mysqli_real_escape_string($conn, $_POST['last_name']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $bio     = mysqli_real_escape_string($conn, $_POST['bio']);

    $sql = "
        UPDATE author
        SET first_name='$first',
            last_name='$last',
            country='$country',
            bio='$bio'
        WHERE author_id = $id
    ";
    mysqli_query($conn, $sql);
    header("Location: authors.php");
    exit;
}
?>

<div style="margin-top:120px;"></div>

<div class="container col-md-6">
    <h2>Edit Author</h2>
    <form method="post">
        <div class="mb-3">
            <label>First name</label>
            <input name="first_name" class="form-control"
                   value="<?= htmlspecialchars($author['first_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Last name</label>
            <input name="last_name" class="form-control"
                   value="<?= htmlspecialchars($author['last_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Country</label>
            <input name="country" class="form-control"
                   value="<?= htmlspecialchars($author['country']); ?>">
        </div>
        <div class="mb-3">
            <label>Bio</label>
            <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($author['bio']); ?></textarea>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="authors.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
