<?php 
include 'navbar.php'; 
require 'db.php';

$isAdmin = (($_SESSION['role'] ?? '') === 'admin');

$first = isset($_GET['first_name'])  ? mysqli_real_escape_string($conn, $_GET['first_name']) : '';
$last  = isset($_GET['last_name'])   ? mysqli_real_escape_string($conn, $_GET['last_name']) : '';
$type  = isset($_GET['type_name'])   ? mysqli_real_escape_string($conn, $_GET['type_name']) : '';
$cont  = isset($_GET['contact'])     ? mysqli_real_escape_string($conn, $_GET['contact']) : '';
$id    = isset($_GET['borrower_id']) ? mysqli_real_escape_string($conn, $_GET['borrower_id']) : '';

$sql = "
  SELECT br.*, bt.type_name
  FROM borrower br
  JOIN borrowertype bt ON br.type_id = bt.type_id
  WHERE 1=1
";

if ($first) $sql .= " AND br.first_name LIKE '%$first%' ";
if ($last)  $sql .= " AND br.last_name  LIKE '%$last%' ";
if ($type)  $sql .= " AND bt.type_name  LIKE '%$type%' ";
if ($cont)  $sql .= " AND br.contact_info LIKE '%$cont%' ";
if ($id)    $sql .= " AND br.borrower_id = '$id' ";

$sql .= " ORDER BY br.borrower_id ASC";
?>

<div class="page-wrap">
  <h2>Borrowers</h2>

  <div class="table-container">
    <?php if($isAdmin): ?>
      <a href="add_borrowers.php" class="btn">+ Add Borrower</a>
    <?php endif; ?>

    <form method="get" class="search-bar">
      <input name="first_name"  placeholder="First Name" value="<?= htmlspecialchars($first) ?>">
      <input name="last_name"   placeholder="Last Name"  value="<?= htmlspecialchars($last) ?>">
      <input name="type_name"   placeholder="Type"       value="<?= htmlspecialchars($type) ?>">
      <input name="contact"     placeholder="Contact"    value="<?= htmlspecialchars($cont) ?>">
      <input name="borrower_id" placeholder="ID"         value="<?= htmlspecialchars($id) ?>">
      <button type="submit">Search</button>
      <a href="borrowers.php" class="btn btn-secondary">Reset</a>
    </form>

    <table>
      <tr>
        <th>ID</th><th>Name</th><th>Type</th><th>Contact</th><th>Actions</th>
      </tr>

      <?php 
      $r = mysqli_query($conn, $sql); 
      while($row = mysqli_fetch_assoc($r)): 
      ?>
        <tr>
          <td><?= $row['borrower_id'] ?></td>
          <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
          <td><?= htmlspecialchars($row['type_name']) ?></td>
          <td><?= htmlspecialchars($row['contact_info']) ?></td>
          <td>
            <?php if($isAdmin): ?>
              <a href="edit_borrower.php?id=<?= $row['borrower_id'] ?>">Edit</a> |
              <a href="delete_borrower.php?id=<?= $row['borrower_id'] ?>" onclick="return confirm('Delete?')">Delete</a>
            <?php else: ?>
              <em>View only</em>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>
</div>
