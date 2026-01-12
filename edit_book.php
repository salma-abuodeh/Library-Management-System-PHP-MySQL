<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Load book
$bookRes = mysqli_query($conn, "SELECT * FROM book WHERE book_id = $id");
$bk = mysqli_fetch_assoc($bookRes);

if (!$bk) {
    die("Book not found.");
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
        UPDATE book
        SET
            title = '$title',
            publisher_id = " . ($publisher_id ? $publisher_id : "NULL") . ",
            category = '$category',
            book_type = '$type',
            original_price = '$price',
            available = $available
        WHERE book_id = $id
    ";

    mysqli_query($conn, $sql);

    header("Location: books.php");
    exit;
}
?>

<div style="margin-top:120px;"></div>

<div class="container col-md-6">
    <h2>Edit Book</h2>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control"
                   value="<?= htmlspecialchars($bk['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Publisher</label>
            <select name="publisher_id" class="form-select">
                <option value="">-- None --</option>
                <?php while ($pub = mysqli_fetch_assoc($pubResult)) { ?>
                    <option value="<?= $pub['publisher_id']; ?>"
                        <?= $bk['publisher_id'] == $pub['publisher_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($pub['name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <input name="category" class="form-control"
                   value="<?= htmlspecialchars($bk['category']); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <input name="book_type" class="form-control"
                   value="<?= htmlspecialchars($bk['book_type']); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Original Price</label>
            <input name="original_price" type="number" step="0.01"
                   class="form-control"
                   value="<?= $bk['original_price']; ?>">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="available" class="form-check-input" id="avail"
                <?= $bk['available'] ? 'checked' : ''; ?>>
            <label for="avail" class="form-check-label">Available</label>
        </div>

       <button class="btn btn-primary">Update</button>
       <a href="books.php" class="btn btn-secondary" style="margin-left:10px;">Cancel</a>

        <a href="books.php" class="btn btn-secondary
