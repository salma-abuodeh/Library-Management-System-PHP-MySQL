<?php 
include 'navbar.php'; 
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$borrowers = mysqli_query($conn, "SELECT * FROM borrower ORDER BY first_name, last_name");
$books = mysqli_query($conn, "SELECT * FROM book WHERE available = 1 ORDER BY title");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bid = intval($_POST['borrower_id']);
    $bk  = intval($_POST['book_id']);
    $ld  = mysqli_real_escape_string($conn, $_POST['loan_date']);
    $dd  = mysqli_real_escape_string($conn, $_POST['due_date']);

    $sql = "INSERT INTO loan(borrower_id, book_id, loan_date, due_date)
            VALUES ($bid, $bk, '$ld', '$dd')";
    mysqli_query($conn, $sql);

    mysqli_query($conn, "UPDATE book SET available = 0 WHERE book_id = $bk");

    header("Location: loans.php");
    exit;
}
?>

<!-- style.css is already loaded by navbar.php -->

<div class="container col-md-6">
    <h2>Add Loan</h2>
    <form method="post">
        <div class="mb-3">
            <label>Borrower</label>
            <select name="borrower_id" class="form-select" required>
                <option value="">-- Select Borrower --</option>
                <?php while ($b = mysqli_fetch_assoc($borrowers)) { ?>
                    <option value="<?= $b['borrower_id'] ?>">
                        <?= htmlspecialchars($b['first_name'] . " " . $b['last_name']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Book</label>
            <select name="book_id" class="form-select" required>
                <option value="">-- Select Book --</option>
                <?php while ($b = mysqli_fetch_assoc($books)) { ?>
                    <option value="<?= $b['book_id'] ?>">
                        <?= htmlspecialchars($b['title']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Loan Date</label>
            <input type="date" name="loan_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="loans.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>