<?php
include 'navbar.php';
require 'db.php';
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
/* ---------- filtering ---------- */
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn,$_GET['search']) : '';
$cat    = isset($_GET['category']) ? mysqli_real_escape_string($conn,$_GET['category']) : '';
$pub    = isset($_GET['publisher']) ? mysqli_real_escape_string($conn,$_GET['publisher']) : '';
$avail  = isset($_GET['available']) ? $_GET['available'] : '';
$sql = "
  SELECT b.*, p.name AS publisher_name
  FROM book b
  LEFT JOIN publisher p ON b.publisher_id = p.publisher_id
  WHERE 1=1 ";
if($search)  $sql .= " AND (b.title LIKE '%$search%' OR b.book_id='$search') ";
if($cat)     $sql .= " AND b.category LIKE '%$cat%' ";
if($pub)     $sql .= " AND p.name LIKE '%$pub%' ";
if(in_array($avail,['0','1'])) $sql .= " AND b.available=$avail ";
$sql .= " ORDER BY b.book_id DESC ";
?>
<!-- style.css is already loaded by navbar.php -->
<h2>Books</h2>
<div class="table-container">
<?php if($isAdmin): ?>
  <a href="add_book.php" class="btn">+ Add Book</a>
<?php endif; ?>

<!-- ===== SEARCH BAR ===== -->
<form method="get" class="search-bar">
  <input name="search" placeholder="Title or ID" value="<?=htmlspecialchars($search)?>">
  <input name="category" placeholder="Category" value="<?=htmlspecialchars($cat)?>">
  <input name="publisher" placeholder="Publisher" value="<?=htmlspecialchars($pub)?>">
  <select name="available">
     <option value="">All</option>
     <option value="1" <?=($avail==='1'?'selected':'')?>>Available</option>
     <option value="0" <?=($avail==='0'?'selected':'')?>>Not Available</option>
  </select>
  <button type="submit">Search</button>
  <a href="books.php" class="small-btn" style="margin:0; display: inline-flex; align-items: center; justify-content: center;">Reset</a>
</form>

<!-- ===== RESPONSIVE TABLE WRAPPER ===== -->
<div class="table-responsive-wrapper">
  <table class="books-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Category</th>
        <th>Type</th>
        <th>Price</th>
        <th>Publisher</th>
        <th>Available</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $r=mysqli_query($conn,$sql); 
      if(mysqli_num_rows($r) > 0): 
        while($row=mysqli_fetch_assoc($r)): 
      ?>
      <tr>
        <td><?=$row['book_id']?></td>
        <td><?=htmlspecialchars($row['title'])?></td>
        <td><?=htmlspecialchars($row['category'])?></td>
        <td><?=htmlspecialchars($row['book_type'])?></td>
        <td>$<?=number_format($row['original_price'],2)?></td>
        <td><?=htmlspecialchars($row['publisher_name'])?></td>
        <td><?=$row['available']?'✅':'❌'?></td>
        <td>
          <?php if($isAdmin): ?>
            <a href="edit_book.php?id=<?=$row['book_id']?>">Edit</a> |
            <a href="delete_book.php?id=<?=$row['book_id']?>" onclick="return confirm('Delete this book?')">Delete</a>
          <?php else: ?>
            <em>View only</em>
          <?php endif; ?>
        </td>
      </tr>
      <?php 
        endwhile; 
      else: 
      ?>
      <tr>
        <td colspan="8" style="text-align: center; padding: 30px; color: #850E35;">
          No books found. Try a different search.
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</div>

<script>
// Add touch scrolling enhancement for mobile
document.addEventListener('DOMContentLoaded', function() {
    const tableWrapper = document.querySelector('.table-responsive-wrapper');
    if (tableWrapper && 'ontouchstart' in window) {
        tableWrapper.style.cursor = 'grab';
        
        let isDown = false;
        let startX;
        let scrollLeft;
        
        tableWrapper.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - tableWrapper.offsetLeft;
            scrollLeft = tableWrapper.scrollLeft;
            tableWrapper.style.cursor = 'grabbing';
        });
        
        tableWrapper.addEventListener('mouseleave', () => {
            isDown = false;
            tableWrapper.style.cursor = 'grab';
        });
        
        tableWrapper.addEventListener('mouseup', () => {
            isDown = false;
            tableWrapper.style.cursor = 'grab';
        });
        
        tableWrapper.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - tableWrapper.offsetLeft;
            const walk = (x - startX) * 2;
            tableWrapper.scrollLeft = scrollLeft - walk;
        });
    }
});
</script>