<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$borRes = mysqli_query($conn, "SELECT * FROM borrower WHERE borrower_id = $id");
$br = mysqli_fetch_assoc($borRes);

if (!$br) {
    die("Borrower not found.");
}

$typeRes = mysqli_query($conn, "SELECT type_id, type_name FROM borrowertype ORDER BY type_name");

/* ---------- server-side e-mail check ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first  = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last   = mysqli_real_escape_string($conn, $_POST['last_name']);
    $typeId = !empty($_POST['type_id']) ? intval($_POST['type_id']) : 'NULL';
    $contact = trim($_POST['contact_info']);

    if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
        die('<div class="alert alert-danger">Please enter a valid e-mail address.</div>');
    }
    $contact = mysqli_real_escape_string($conn, $contact);

    $sql = "
        UPDATE borrower
        SET first_name = '$first',
            last_name = '$last',
            type_id = $typeId,
            contact_info = '$contact'
        WHERE borrower_id = $id
    ";
    mysqli_query($conn, $sql);

    header("Location: borrowers.php");
    exit;
}
?>

<div style="margin-top:120px;"></div>

<div class="container col-md-6">
    <h2>Edit Borrower</h2>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input name="first_name" class="form-control"
                   value="<?= htmlspecialchars($br['first_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input name="last_name" class="form-control"
                   value="<?= htmlspecialchars($br['last_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type_id" class="form-select">
                <option value="">-- None --</option>
                <?php while ($t = mysqli_fetch_assoc($typeRes)) { ?>
                    <option value="<?= $t['type_id']; ?>"
                        <?= $br['type_id'] == $t['type_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($t['type_name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Info (e-mail)</label>
            <input name="contact_info" type="email" class="form-control" required
                   value="<?= htmlspecialchars($br['contact_info']); ?>"
                   placeholder="user@example.com">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="borrowers.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>