<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$q = mysqli_query($conn, "SELECT * FROM loan WHERE loan_id = $id");
$l = mysqli_fetch_assoc($q);

if (!$l) {
    die("Loan not found.");
}

$bookId = (int)$l['book_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ld = mysqli_real_escape_string($conn, $_POST['loan_date']);
    $dd = mysqli_real_escape_string($conn, $_POST['due_date']);
    $rd = $_POST['return_date'];

    // allow NULL return date
    $rdValue = $rd !== '' ? "'" . mysqli_real_escape_string($conn, $rd) . "'" : "NULL";

    $sql = "UPDATE loan SET 
                loan_date = '$ld',
                due_date  = '$dd',
                return_date = $rdValue
            WHERE loan_id = $id";

    mysqli_query($conn, $sql);

    // if book returned, mark it available again
    if ($rd !== '' && $bookId > 0) {
        mysqli_query($conn, "UPDATE book SET available = 1 WHERE book_id = $bookId");
    }

    header("Location: loans.php");
    exit;
}
?>

<!-- style.css is already loaded by navbar.php -->

<div class="container col-md-6">
    <h2>Edit Loan</h2>
    <form method="post">
        <div class="mb-3">
            <label>Loan Date</label>
            <input type="date" name="loan_date" class="form-control"
                   value="<?= htmlspecialchars($l['loan_date']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control"
                   value="<?= htmlspecialchars($l['due_date']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Return Date</label>
            <input type="date" name="return_date" class="form-control"
                   value="<?= htmlspecialchars($l['return_date']); ?>">
            <small class="form-text text-muted">Leave empty if not yet returned</small>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="loans.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>