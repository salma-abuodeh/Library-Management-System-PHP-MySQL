<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

// Load publishers for dropdown
$pubResult = mysqli_query($conn, "SELECT publisher_id, name FROM publisher ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title    = mysqli_real_escape_string($conn, $_POST['title']);
    $publisher_id = !empty($_POST['publisher_id']) ? intval($_POST['publisher_id']) : NULL;
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $type     = mysqli_real_escape_string($conn, $_POST['book_type']);
    $price    = mysqli_real_escape_string($conn, $_POST['original_price']);
    $available = isset($_POST['available']) ? 1 : 0;

    $sql = "
        INSERT INTO book(title, publisher_id, category, book_type, original_price, available)
        VALUES(" .
            "'" . $title . "'," .
            ($publisher_id ? $publisher_id : "NULL") . "," .
            "'" . $category . "'," .
            "'" . $type . "'," .
            "'" . $price . "'," .
            $available .
        ")
    ";

    mysqli_query($conn, $sql);
    header("Location: books.php");
    exit;
}
?>

<div style="margin-top:120px;"></div>
<div class="container col-md-6">
    <h2>Add Book</h2>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Publisher</label>
            <select name="publisher_id" class="form-select">
                <option value="">-- None --</option>
                <?php while ($pub = mysqli_fetch_assoc($pubResult)) { ?>
                    <option value="<?= $pub['publisher_id']; ?>">
                        <?= htmlspecialchars($pub['name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <input name="category" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <input name="book_type" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Original Price</label>
            <input name="original_price" type="number" step="0.01" class="form-control">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="available" class="form-check-input" checked id="avail">
            <label for="avail" class="form-check-label">Available</label>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="books.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
