<?php 
include 'navbar.php'; 
require 'db.php';

$isAdmin = (($_SESSION['role'] ?? '') === 'admin');

$name    = isset($_GET['name'])         ? mysqli_real_escape_string($conn, $_GET['name']) : '';
$city    = isset($_GET['city'])         ? mysqli_real_escape_string($conn, $_GET['city']) : '';
$country = isset($_GET['country'])      ? mysqli_real_escape_string($conn, $_GET['country']) : '';
$id      = isset($_GET['publisher_id']) ? mysqli_real_escape_string($conn, $_GET['publisher_id']) : '';

$sql = "SELECT * FROM publisher WHERE 1=1 ";
if($name)    $sql .= " AND name LIKE '%$name%' ";
if($city)    $sql .= " AND city LIKE '%$city%' ";
if($country) $sql .= " AND country LIKE '%$country%' ";
if($id)      $sql .= " AND publisher_id = '$id' ";
$sql .= " ORDER BY publisher_id ASC";
?>

<div class="page-wrap">
  <h2>Publishers</h2>

  <div class="table-container">
    <?php if($isAdmin): ?>
      <a href="add_publisher.php" class="btn">+ Add Publisher</a>
    <?php endif; ?>

    <form method="get" class="search-bar">
      <input name="name" placeholder="Publisher Name" value="<?= htmlspecialchars($name) ?>">
      <input name="city" placeholder="City" value="<?= htmlspecialchars($city) ?>">
      <input name="country" placeholder="Country" value="<?= htmlspecialchars($country) ?>">
      <input name="publisher_id" placeholder="ID" value="<?= htmlspecialchars($id) ?>">
      <button type="submit">Search</button>
      <a href="publishers.php" class="btn btn-secondary">Reset</a>
    </form>

    <table>
      <tr>
        <th>ID</th><th>Name</th><th>City</th><th>Country</th><th>Contact</th><th>Actions</th>
      </tr>

      <?php 
      $r = mysqli_query($conn, $sql); 
      while($row = mysqli_fetch_assoc($r)): 
      ?>
        <tr>
          <td><?= $row['publisher_id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['city']) ?></td>
          <td><?= htmlspecialchars($row['country']) ?></td>
          <td><?= htmlspecialchars($row['contact_info']) ?></td>
          <td>
            <?php if($isAdmin): ?>
              <a href="edit_publisher.php?id=<?= $row['publisher_id'] ?>">Edit</a> |
              <a href="delete_publisher.php?id=<?= $row['publisher_id'] ?>" onclick="return confirm('Delete?')">Delete</a>
            <?php else: ?>
              <em>View only</em>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>
</div>
