<?php include 'navbar.php'; require 'db.php';
$isAdmin = ($_SESSION['role']??'')==='admin';

$bf     = isset($_GET['bf'])     ? mysqli_real_escape_string($conn,$_GET['bf']) : '';
$bl     = isset($_GET['bl'])     ? mysqli_real_escape_string($conn,$_GET['bl']) : '';
$btitle = isset($_GET['btitle']) ? mysqli_real_escape_string($conn,$_GET['btitle']) : '';
$from   = isset($_GET['from'])   ? mysqli_real_escape_string($conn,$_GET['from']) : '';
$to     = isset($_GET['to'])     ? mysqli_real_escape_string($conn,$_GET['to']) : '';
$id     = isset($_GET['loan_id'])? mysqli_real_escape_string($conn,$_GET['loan_id']) : '';

$sql = "
  SELECT l.*, b.title, CONCAT(br.first_name,' ',br.last_name) AS borrower_name
  FROM loan l
  JOIN book b ON l.book_id=b.book_id
  JOIN borrower br ON l.borrower_id=br.borrower_id
  WHERE 1=1 ";
if($bf)     $sql.=" AND br.first_name LIKE '%$bf%' ";
if($bl)     $sql.=" AND br.last_name  LIKE '%$bl%' ";
if($btitle) $sql.=" AND b.title       LIKE '%$btitle%' ";
if($from)   $sql.=" AND l.loan_date   >='$from' ";
if($to)     $sql.=" AND l.loan_date   <='$to' ";
if($id)     $sql.=" AND l.loan_id     = '$id' ";
$sql.="ORDER BY l.loan_id DESC";
?>
<h2>Loans</h2>
<div class="table-container">
<?php if($isAdmin): ?><a href="add_loan.php" class="btn">+ Add Loan</a><?php endif; ?>
<form method="get" class="search-bar">
  <input name="bf"     placeholder="Borrower First" value="<?=htmlspecialchars($bf)?>">
  <input name="bl"     placeholder="Borrower Last"  value="<?=htmlspecialchars($bl)?>">
  <input name="btitle" placeholder="Book Title"     value="<?=htmlspecialchars($btitle)?>">
  <input type="date" name="from" value="<?=htmlspecialchars($from)?>" title="Loan date from">
  <input type="date" name="to"   value="<?=htmlspecialchars($to)?>"   title="Loan date to">
  <input name="loan_id" placeholder="Loan ID"      value="<?=htmlspecialchars($id)?>">
  <button>Search</button>
  <a href="loans.php" class="btn btn-secondary">Reset</a>
</form>
<table>
  <tr><th>ID</th><th>Borrower</th><th>Book</th><th>Loan Date</th><th>Due Date</th><th>Return Date</th><th>Actions</th></tr>
<?php $r=mysqli_query($conn,$sql); while($row=mysqli_fetch_assoc($r)): ?>
  <tr>
     <td><?=$row['loan_id']?></td>
     <td><?=htmlspecialchars($row['borrower_name'])?></td>
     <td><?=htmlspecialchars($row['title'])?></td>
     <td><?=$row['loan_date']?></td>
     <td><?=$row['due_date']?></td>
     <td><?=$row['return_date']??'—'?></td>
     <td>
        <?php if($isAdmin): ?>
           <a href="edit_loan.php?id=<?=$row['loan_id']?>">Edit</a> |
           <a href="delete_loan.php?id=<?=$row['loan_id']?>" onclick="return confirm('Delete?')">Delete</a>
        <?php else: ?><em>View only</em><?php endif; ?>
     </td>
  </tr>
<?php endwhile; ?>
</table>
</div>