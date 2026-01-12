<?php
include 'navbar.php';
require 'db.php';


if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

// Get available books (available = 1)
$bookRes = mysqli_query($conn, "
    SELECT book_id, title, original_price
    FROM book
    WHERE available = 1
    ORDER BY title
");

// Get all borrowers
$borRes = mysqli_query($conn, "
    SELECT borrower_id, first_name, last_name
    FROM borrower
    ORDER BY first_name, last_name
");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $book_id     = intval($_POST['book_id']);
    $borrower_id = intval($_POST['borrower_id']);
    $sale_date   = mysqli_real_escape_string($conn, $_POST['sale_date']);
    $sale_price  = mysqli_real_escape_string($conn, $_POST['sale_price']);

    $sql = "
        INSERT INTO sale(book_id, borrower_id, sale_date, sale_price)
        VALUES($book_id, $borrower_id, '$sale_date', '$sale_price')
    ";
    mysqli_query($conn, $sql);

    // ensure availability set to 0
    mysqli_query($conn, "UPDATE book SET available = 0 WHERE book_id = $book_id");

    header("Location: sales.php");
    exit;
}
?>

<div style="margin-top:120px;"></div>

<div class="container col-md-6">
    <h2>Add Sale</h2>

    <form method="post">
        <div class="mb-3">
            <label>Book</label>
            <select name="book_id" class="form-select" required>
                <option value="">-- Select Book --</option>
                <?php while ($b = mysqli_fetch_assoc($bookRes)) { ?>
                    <option value="<?= $b['book_id']; ?>">
                        <?= htmlspecialchars($b['title']); ?> (Price: <?= $b['original_price']; ?>)
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Borrower</label>
            <select name="borrower_id" class="form-select" required>
                <option value="">-- Select Borrower --</option>
                <?php while ($br = mysqli_fetch_assoc($borRes)) { ?>
                    <option value="<?= $br['borrower_id']; ?>">
                        <?= htmlspecialchars($br['first_name'] . ' ' . $br['last_name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Sale Date</label>
            <input type="date" name="sale_date" class="form-control"
                   value="<?= date('Y-m-d'); ?>" required>
        </div>

        <div class="mb-3">
            <label>Sale Price</label>
            <input type="number" step="0.01" name="sale_price"
                   class="form-control" required>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="sales.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
