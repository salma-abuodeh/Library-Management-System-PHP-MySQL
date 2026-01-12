<?php include 'navbar.php'; require 'db.php';
$isAdmin = ($_SESSION['role']??'')==='admin';

$sf     = isset($_GET['sf'])     ? mysqli_real_escape_string($conn,$_GET['sf']) : '';
$sl     = isset($_GET['sl'])     ? mysqli_real_escape_string($conn,$_GET['sl']) : '';
$btitle = isset($_GET['btitle']) ? mysqli_real_escape_string($conn,$_GET['btitle']) : '';
$from   = isset($_GET['from'])   ? mysqli_real_escape_string($conn,$_GET['from']) : '';
$to     = isset($_GET['to'])     ? mysqli_real_escape_string($conn,$_GET['to']) : '';
$id     = isset($_GET['sale_id'])? mysqli_real_escape_string($conn,$_GET['sale_id']) : '';

$sql = "
  SELECT s.*, b.title, CONCAT(br.first_name,' ',br.last_name) AS buyer
  FROM sale s
  JOIN book b ON s.book_id=b.book_id
  JOIN borrower br ON s.borrower_id=br.borrower_id
  WHERE 1=1 ";
if($sf)     $sql.=" AND br.first_name LIKE '%$sf%' ";
if($sl)     $sql.=" AND br.last_name  LIKE '%$sl%' ";
if($btitle) $sql.=" AND b.title       LIKE '%$btitle%' ";
if($from)   $sql.=" AND s.sale_date   >='$from' ";
if($to)     $sql.=" AND s.sale_date   <='$to' ";
if($id)     $sql.=" AND s.sale_id     = '$id' ";
$sql.="ORDER BY s.sale_date DESC";
?>
<h2>Sales</h2>
<div class="table-container">
<?php if($isAdmin): ?><a href="add_sale.php" class="btn">+ Add Sale</a><?php endif; ?>
<form method="get" class="search-bar">
  <input name="sf"     placeholder="Buyer First" value="<?=htmlspecialchars($sf)?>">
  <input name="sl"     placeholder="Buyer Last"  value="<?=htmlspecialchars($sl)?>">
  <input name="btitle" placeholder="Book Title"  value="<?=htmlspecialchars($btitle)?>">
  <input type="date" name="from" value="<?=htmlspecialchars($from)?>" title="Sale date from">
  <input type="date" name="to"   value="<?=htmlspecialchars($to)?>"   title="Sale date to">
  <input name="sale_id" placeholder="Sale ID"     value="<?=htmlspecialchars($id)?>">
  <button>Search</button>
  <a href="sales.php" class="btn btn-secondary">Reset</a>
</form>
<table>
  <tr><th>ID</th><th>Book</th><th>Buyer</th><th>Date</th><th>Price</th><th>Actions</th></tr>
<?php $r=mysqli_query($conn,$sql); while($row=mysqli_fetch_assoc($r)): ?>
  <tr>
     <td><?=$row['sale_id']?></td>
     <td><?=htmlspecialchars($row['title'])?></td>
     <td><?=htmlspecialchars($row['buyer'])?></td>
     <td><?=$row['sale_date']?></td>
     <td><?=number_format($row['sale_price'],2)?></td>
     <td>
        <?php if($isAdmin): ?>
           <a href="edit_sale.php?id=<?=$row['sale_id']?>">Edit</a> |
           <a href="delete_sale.php?id=<?=$row['sale_id']?>" onclick="return confirm('Delete?')">Delete</a>
        <?php else: ?><em>View only</em><?php endif; ?>
     </td>
  </tr>
<?php endwhile; ?>
</table>
</div>