<?php include 'navbar.php'; require 'db.php';
$isAdmin = ($_SESSION['role']??'')==='admin';

$first  = isset($_GET['first_name']) ? mysqli_real_escape_string($conn,$_GET['first_name']) : '';
$last   = isset($_GET['last_name'])  ? mysqli_real_escape_string($conn,$_GET['last_name']) : '';
$country= isset($_GET['country'])    ? mysqli_real_escape_string($conn,$_GET['country']) : '';
$id     = isset($_GET['author_id'])  ? mysqli_real_escape_string($conn,$_GET['author_id']) : '';

$sql = "SELECT * FROM author WHERE 1=1 ";
if($first)   $sql.=" AND first_name LIKE '%$first%' ";
if($last)    $sql.=" AND last_name  LIKE '%$last%' ";
if($country) $sql.=" AND country    LIKE '%$country%' ";
if($id)      $sql.=" AND author_id  = '$id' ";
$sql.="ORDER BY author_id DESC";
?>
<!-- body padding-top (in style.css) already accounts for the fixed navbar -->
<h2>Authors</h2>
<div class="table-container">
<?php if($isAdmin): ?><a href="add_author.php" class="btn">+ Add Author</a><?php endif; ?>
<form method="get" class="search-bar">
  <input name="first_name" placeholder="First Name" value="<?=htmlspecialchars($first)?>">
  <input name="last_name"  placeholder="Last Name"  value="<?=htmlspecialchars($last)?>">
  <input name="country"    placeholder="Country"    value="<?=htmlspecialchars($country)?>">
  <input name="author_id"  placeholder="ID"         value="<?=htmlspecialchars($id)?>">
  <button>Search</button>
  <a href="authors.php" class="btn btn-secondary">Reset</a>
</form>
<table>
  <tr><th>ID</th><th>Name</th><th>Country</th><th>Bio</th><th>Actions</th></tr>
<?php $r=mysqli_query($conn,$sql); while($row=mysqli_fetch_assoc($r)): ?>
  <tr>
     <td><?=$row['author_id']?></td>
     <td><?=htmlspecialchars($row['first_name'].' '.$row['last_name'])?></td>
     <td><?=htmlspecialchars($row['country'])?></td>
     <td><?=nl2br(htmlspecialchars($row['bio']))?></td>
     <td>
        <?php if($isAdmin): ?>
           <a href="edit_author.php?id=<?=$row['author_id']?>">Edit</a> |
           <a href="delete_author.php?id=<?=$row['author_id']?>" onclick="return confirm('Delete?')">Delete</a>
        <?php else: ?><em>View only</em><?php endif; ?>
     </td>
  </tr>
<?php endwhile; ?>
</table>
</div>