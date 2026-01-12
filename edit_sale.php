<?php
include 'navbar.php';
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: sales.php");
    exit;
}

$id = (int) $_GET['id'];

// Load existing sale
$sql = "
    SELECT s.sale_id,
           s.sale_date,
           s.sale_price,
           b.title AS book_title,
           br.first_name,
           br.last_name
    FROM sale s
    JOIN book b ON s.book_id = b.book_id
    JOIN borrower br ON s.borrower_id = br.borrower_id
    WHERE s.sale_id = $id
";
$r = mysqli_query($conn, $sql);
$sale = mysqli_fetch_assoc($r);

if (!$sale) {
    header("Location: sales.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sale_date  = mysqli_real_escape_string($conn, $_POST['sale_date']);
    $sale_price = (float) $_POST['sale_price'];

    if ($sale_date === "" || $sale_price <= 0) {
        $error = "Please enter a valid date and price.";
    } else {
        $update = "
            UPDATE sale
            SET sale_date = '$sale_date',
                sale_price = $sale_price
            WHERE sale_id = $id
        ";
        mysqli_query($conn, $update);
        header("Location: sales.php");
        exit;
    }
}
?>

<div style="margin-top:120px;"></div>

<div class="container" style="margin-top:120px; max-width:600px;">
    <h2>Edit Sale</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Book</label>
            <input type="text" class="form-control"
                   value="<?= htmlspecialchars($sale['book_title']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label>Buyer (Borrower)</label>
            <input type="text" class="form-control"
                   value="<?= htmlspecialchars($sale['first_name'] . ' ' . $sale['last_name']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label>Sale Date</label>
            <input type="date" name="sale_date" class="form-control"
                   value="<?= htmlspecialchars($sale['sale_date']) ?>">
        </div>

        <div class="mb-3">
            <label>Sale Price</label>
            <input type="number" step="0.01" name="sale_price" class="form-control"
                   value="<?= htmlspecialchars($sale['sale_price']) ?>">
        </div>

        <button class="btn btn-primary">Save Changes</button>
        <a href="sales.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
