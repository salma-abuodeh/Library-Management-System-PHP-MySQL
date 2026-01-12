<?php
include 'navbar.php';
require 'db.php';

$authors = mysqli_query($conn, "SELECT author_id, first_name, last_name FROM author ORDER BY first_name, last_name");
$borrowers = mysqli_query($conn, "SELECT borrower_id, first_name, last_name FROM borrower ORDER BY first_name, last_name");
$countries = mysqli_query($conn, "SELECT DISTINCT country FROM publisher WHERE country IS NOT NULL ORDER BY country");

$report = isset($_GET['report']) ? $_GET['report'] : '';

$author_id   = isset($_GET['author_id']) ? intval($_GET['author_id']) : 0;
$borrower_id = isset($_GET['borrower_id']) ? intval($_GET['borrower_id']) : 0;
$country     = isset($_GET['country']) ? mysqli_real_escape_string($conn, $_GET['country']) : '';
$from_date   = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date     = isset($_GET['to_date']) ? $_GET['to_date'] : '';
?>

<style>
.reports-container {
    display: flex;
    gap: 32px;
    width: 90%;
    max-width: 1400px;
    margin: 0 auto 80px;    
    position: relative;
    z-index: 1;
}


.sidebar {
    width: 320px;
    background: #FCF5EE;
    color: #850E35;
    border-radius: 25px;
    padding: 35px;
    height: fit-content;
    box-shadow: 0 18px 50px rgba(133, 14, 53, 0.3);
    border: 3px solid rgba(255, 196, 196, 0.5);
}

.sidebar h3 {
    margin-bottom: 28px;
    color: #850E35;
    font-size: 26px;
    font-weight: 800;
    text-align: center;
    padding-bottom: 18px;
    border-bottom: 4px solid #FFC4C4;
}

.sidebar a {
    display: block;
    padding: 18px 22px;
    margin-bottom: 14px;
    background: linear-gradient(135deg, rgba(255, 196, 196, 0.35), rgba(238, 105, 131, 0.25));
    color: #850E35;
    text-decoration: none;
    border-radius: 18px;
    font-size: 16px;
    font-weight: 700;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(238, 105, 131, 0.18);
    border-left: 5px solid transparent;
}

.sidebar a:hover {
    background: linear-gradient(135deg, #EE6983 0%, #850E35 100%);
    color: #FCF5EE;
    transform: translateX(10px);
    box-shadow: 0 8px 20px rgba(238, 105, 131, 0.35);
    border-left-color: #FCF5EE;
}

.sidebar .active {
    background: linear-gradient(135deg, #850E35 0%, #EE6983 100%);
    color: #FCF5EE;
    box-shadow: 0 10px 25px rgba(133, 14, 53, 0.45);
    border-left-color: #FCF5EE;
    transform: translateX(8px);
}

.sidebar hr {
    border-color: rgba(255, 196, 196, 0.5);
    margin: 28px 0;
    border-width: 2px;
}

.report-box {
    flex-grow: 1;
    background: #FCF5EE;
    padding: 40px;
    border-radius: 25px;
    box-shadow: 0 18px 50px rgba(133, 14, 53, 0.3);
    border: 3px solid rgba(255, 196, 196, 0.5);
}

.report-box h2 {
    color: #850E35;
    font-size: 28px;
    font-weight: 700;
    text-shadow: none;
}

.report-box h3 {
    color: #850E35;
    font-size: 24px;
    font-weight: 700;
}

.report-box h4 {
    color: #850E35;
    font-size: 20px;
    font-weight: 700;
    margin-top: 25px;
    margin-bottom: 15px;
}

.report-title {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 28px;
    color: #850E35;
    border-bottom: 5px solid #EE6983;
    padding-bottom: 18px;
    position: relative;
}

.report-title::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 120px;
    height: 5px;
    background: #FFC4C4;
    border-radius: 5px;
}

.no-results {
    padding: 22px;
    color: #850E35;
    font-style: italic;
    background: linear-gradient(135deg, rgba(255, 196, 196, 0.35), rgba(238, 105, 131, 0.15));
    border-radius: 18px;
    text-align: center;
    font-weight: 600;
    border: 3px dashed #EE6983;
    font-size: 16px;
}

.report-box label {
    color: #850E35;
    font-weight: 700;
    font-size: 15px;
    margin-bottom: 8px;
    display: block;
}

.report-box .form-select,
.report-box .form-control {
    border: 2px solid rgba(255, 196, 196, 0.5);
    border-radius: 12px;
    padding: 12px 16px;
    color: #850E35;
    font-weight: 600;
    transition: all 0.3s ease;
}

.report-box .form-select:focus,
.report-box .form-control:focus {
    border-color: #EE6983;
    box-shadow: 0 0 0 4px rgba(238, 105, 131, 0.15);
    outline: none;
}

@media (max-width: 768px) {
    .reports-container {
        flex-direction: column;
        width: 95%;
    }
    
    .sidebar {
        width: 100%;
    }
}
/* ===== FIX: REPORT TABLE HEADERS COLOR ===== */
.report-box .table thead th,
.report-box .table thead td {
    color: #FCF5EE !important;                 /* white */
    background: linear-gradient(135deg, #850E35 0%, #EE6983 100%) !important;
    border-color: rgba(255, 196, 196, 0.6) !important;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.2);
}

.report-box .table tbody td {
    color: #850E35 !important;                
}

/* optional: nicer hover inside reports tables */
.report-box .table tbody tr:hover td {
    background: linear-gradient(90deg, #FFC4C4 0%, rgba(255, 196, 196, 0.6) 100%) !important;
}

</style>


<div class="reports-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>📊 Reports Menu</h3>

        <a href="reports.php?report=author" class="<?= $report=='author'?'active':'' ?>">📚 Books by Author</a>
        <a href="reports.php?report=borrower" class="<?= $report=='borrower'?'active':'' ?>">👤 Borrower Activity</a>
        <a href="reports.php?report=country" class="<?= $report=='country'?'active':'' ?>">🌍 Books by Country</a>
        <a href="reports.php?report=date" class="<?= $report=='date'?'active':'' ?>">🗓️ Loans by Date</a>

        <hr>

        <a href="reports.php?report=currentloans" class="<?= $report=='currentloans'?'active':'' ?>">🔁 Current Loans</a>
        <a href="reports.php?report=sales" class="<?= $report=='sales'?'active':'' ?>">💰 Sales</a>
        <a href="reports.php?report=available" class="<?= $report=='available'?'active':'' ?>">📖 Available Books</a>
        <a href="reports.php?report=category" class="<?= $report=='category'?'active':'' ?>">📊 Books per Category</a>
        <a href="reports.php?report=neverborrowed" class="<?= $report=='neverborrowed'?'active':'' ?>">🚫 Never Borrowed</a>
        <a href="reports.php?report=multiauthor" class="<?= $report=='multiauthor'?'active':'' ?>">📚 Books with >1 Author</a>

        <hr>

        <a href="reports.php?report=value" class="<?= $report=='value'?'active':'' ?>">💵 Total Value of Books</a>
    </div>

    <!-- Right panel -->
    <div class="report-box">

        <?php if ($report == '') { ?>
            <h2>Select a report from the left menu.</h2>

        <?php } ?>


        
        <?php if ($report == 'author') { ?>
            <div class="report-title">📚 Books By Author</div>

            <form method="get">
                <input type="hidden" name="report" value="author">

                <div class="mb-3">
                    <label>Select Author:</label>
                    <select name="author_id" class="form-select" style="max-width:350px;">
                        <option value="0">-- Choose Author --</option>
                        <?php mysqli_data_seek($authors, 0);
                        while ($a = mysqli_fetch_assoc($authors)) { ?>
                            <option value="<?= $a['author_id']; ?>"
                                <?= $author_id == $a['author_id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($a['first_name'] . ' ' . $a['last_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button class="btn btn-primary">Show Results</button>
            </form>

            <?php
            if ($author_id) {
                $sql = "
                    SELECT b.book_id, b.title, b.category, b.book_type
                    FROM book b
                    JOIN bookauthor ba ON b.book_id = ba.book_id
                    WHERE ba.author_id = $author_id
                ";
                $r = mysqli_query($conn, $sql);
                ?>

                <h4 class="mt-4">Results:</h4>

                <?php if (mysqli_num_rows($r) == 0) { ?>
                    <div class="no-results">No books found for this author.</div>
                <?php } else { ?>
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Type</th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($r)) { ?>
                            <tr>
                                <td><?= $row['book_id']; ?></td>
                                <td><?= htmlspecialchars($row['title']); ?></td>
                                <td><?= htmlspecialchars($row['category']); ?></td>
                                <td><?= htmlspecialchars($row['book_type']); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            <?php } ?>
        <?php } ?>



       
        <?php if ($report == 'borrower') { ?>
            <div class="report-title">👤 Borrower Activity</div>

            <form method="get">
                <input type="hidden" name="report" value="borrower">

                <div class="mb-3">
                    <label>Select Borrower:</label>
                    <select name="borrower_id" class="form-select" style="max-width:350px;">
                        <option value="0">-- Choose Borrower --</option>
                        <?php mysqli_data_seek($borrowers, 0);
                        while ($b = mysqli_fetch_assoc($borrowers)) { ?>
                            <option value="<?= $b['borrower_id']; ?>"
                                <?= $borrower_id == $b['borrower_id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button class="btn btn-primary">Show Results</button>
            </form>

            <?php if ($borrower_id) { ?>

                <!-- LOANS -->
                <h4 class="mt-4">Loans:</h4>
                <?php
                $sql_l = "
                    SELECT l.loan_id, b.title, l.loan_date, l.due_date, l.return_date
                    FROM loan l
                    JOIN book b ON l.book_id = b.book_id
                    WHERE l.borrower_id = $borrower_id
                ";
                $loans = mysqli_query($conn, $sql_l);
                ?>

                <?php if (mysqli_num_rows($loans) == 0) { ?>
                    <div class="no-results">No loans found.</div>
                <?php } else { ?>
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>ID</th><th>Book</th><th>Loan</th><th>Due</th><th>Return</th></tr></thead>
                        <tbody>
                        <?php while ($l = mysqli_fetch_assoc($loans)) { ?>
                            <tr>
                                <td><?= $l['loan_id']; ?></td>
                                <td><?= htmlspecialchars($l['title']); ?></td>
                                <td><?= $l['loan_date']; ?></td>
                                <td><?= $l['due_date']; ?></td>
                                <td><?= $l['return_date']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>

                <!-- SALES -->
                <h4>Purchases:</h4>
                <?php
                $sql_s = "
                    SELECT s.sale_id, b.title, s.sale_date, s.sale_price
                    FROM sale s
                    JOIN book b ON s.book_id = b.book_id
                    WHERE s.borrower_id = $borrower_id
                ";
                $sales = mysqli_query($conn, $sql_s);

                if (mysqli_num_rows($sales) == 0) { ?>
                    <div class="no-results">No purchases found.</div>
                <?php } else { ?>
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>ID</th><th>Book</th><th>Date</th><th>Price</th></tr></thead>
                        <tbody>
                        <?php while ($s = mysqli_fetch_assoc($sales)) { ?>
                            <tr>
                                <td><?= $s['sale_id']; ?></td>
                                <td><?= htmlspecialchars($s['title']); ?></td>
                                <td><?= $s['sale_date']; ?></td>
                                <td><?= $s['sale_price']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>

            <?php } ?>
        <?php } ?>



        <!-- ==========================
             REPORT 3 – BOOKS BY COUNTRY
        =========================== -->
        <?php if ($report == 'country') { ?>
            <div class="report-title">🌍 Books Published in a Country</div>

            <form method="get">
                <input type="hidden" name="report" value="country">

                <div class="mb-3">
                    <label>Select Country:</label>
                    <select name="country" class="form-select" style="max-width:350px;">
                        <option value="">-- Choose Country --</option>
                        <?php mysqli_data_seek($countries, 0);
                        while ($c = mysqli_fetch_assoc($countries)) { ?>
                            <option value="<?= htmlspecialchars($c['country']); ?>"
                                <?= $country == $c['country'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($c['country']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button class="btn btn-primary">Show Results</button>
            </form>

            <?php
            if ($country !== '') {
                $sql = "
                    SELECT b.book_id, b.title, b.category, p.name AS publisher
                    FROM book b
                    JOIN publisher p ON b.publisher_id = p.publisher_id
                    WHERE p.country = '$country'
                ";
                $r = mysqli_query($conn, $sql);
                ?>

                <h4 class="mt-4">Results:</h4>

                <?php if (mysqli_num_rows($r) == 0) { ?>
                    <div class="no-results">No books found for this country.</div>
                <?php } else { ?>
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Publisher</th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($r)) { ?>
                            <tr>
                                <td><?= $row['book_id']; ?></td>
                                <td><?= htmlspecialchars($row['title']); ?></td>
                                <td><?= htmlspecialchars($row['category']); ?></td>
                                <td><?= htmlspecialchars($row['publisher']); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            <?php } ?>
        <?php } ?>



        <!-- ==========================
             REPORT 4 – DATE RANGE
        =========================== -->
        <?php if ($report == 'date') { ?>
            <div class="report-title">🗓️ Loans Within Date Range</div>

            <form method="get">
                <input type="hidden" name="report" value="date">

                <div class="row g-2 mb-3" style="max-width:450px;">
                    <div class="col-md-6">
                        <label>From:</label>
                        <input type="date" name="from_date" class="form-control"
                               value="<?= htmlspecialchars($from_date); ?>">
                    </div>
                    <div class="col-md-6">
                        <label>To:</label>
                        <input type="date" name="to_date" class="form-control"
                               value="<?= htmlspecialchars($to_date); ?>">
                    </div>
                </div>

                <button class="btn btn-primary">Show Results</button>
            </form>

            <?php
            if ($from_date !== '' && $to_date !== '') {
                $sql = "
                    SELECT l.loan_id, b.title, br.first_name, br.last_name,
                           l.loan_date, l.due_date
                    FROM loan l
                    JOIN book b ON l.book_id = b.book_id
                    JOIN borrower br ON l.borrower_id = br.borrower_id
                    WHERE l.loan_date BETWEEN '$from_date' AND '$to_date'
                ";
                $r = mysqli_query($conn, $sql);
                ?>

                <h4 class="mt-4">Results:</h4>

                <?php if (mysqli_num_rows($r) == 0) { ?>
                    <div class="no-results">No loans in this date range.</div>
                <?php } else { ?>
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>ID</th><th>Book</th><th>Borrower</th><th>Loan</th><th>Due</th></tr></thead>
                        <tbody>
                        <?php while ($l = mysqli_fetch_assoc($r)) { ?>
                            <tr>
                                <td><?= $l['loan_id']; ?></td>
                                <td><?= htmlspecialchars($l['title']); ?></td>
                                <td><?= htmlspecialchars($l['first_name'] . ' ' . $l['last_name']); ?></td>
                                <td><?= $l['loan_date']; ?></td>
                                <td><?= $l['due_date']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            <?php } ?>
        <?php } ?>




        <?php if ($report == 'currentloans') { ?>
            <div class="report-title">🔁 Current Loans</div>

            <?php
            $sql = "
                SELECT l.loan_id, b.title, br.first_name, br.last_name,
                       l.loan_date, l.due_date
                FROM loan l
                JOIN book b ON l.book_id = b.book_id
                JOIN borrower br ON l.borrower_id = br.borrower_id
                WHERE l.return_date IS NULL
            ";
            $r = mysqli_query($conn, $sql);
            ?>

            <table class="table table-bordered table-sm">
                <thead><tr><th>ID</th><th>Book</th><th>Borrower</th><th>Loan</th><th>Due</th></tr></thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($r)) { ?>
                    <tr>
                        <td><?= $row['loan_id']; ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']); ?></td>
                        <td><?= $row['loan_date']; ?></td>
                        <td><?= $row['due_date']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>




        <?php if ($report == 'sales') { ?>
            <div class="report-title">💰 Sales</div>

            <?php
            $sql = "
                SELECT s.sale_id, b.title, s.sale_date, s.sale_price
                FROM sale s
                JOIN book b ON s.book_id = b.book_id
                ORDER BY s.sale_date DESC
            ";
            $r = mysqli_query($conn, $sql);
            ?>

            <table class="table table-bordered table-sm">
                <thead><tr><th>ID</th><th>Book</th><th>Date</th><th>Price</th></tr></thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($r)) { ?>
                    <tr>
                        <td><?= $row['sale_id']; ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= $row['sale_date']; ?></td>
                        <td><?= $row['sale_price']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>




        <?php if ($report == 'available') { ?>
            <div class="report-title">📖 Available Books</div>

            <?php
            $sql = "SELECT book_id, title, category, book_type FROM book WHERE available = 1";
            $r = mysqli_query($conn, $sql);
            ?>

            <table class="table table-bordered table-sm">
                <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Type</th></tr></thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($r)) { ?>
                    <tr>
                        <td><?= $row['book_id']; ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td><?= htmlspecialchars($row['book_type']); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>




        <?php if ($report == 'category') { ?>
            <div class="report-title">📊 Books per Category</div>

            <?php
            $sql = "SELECT category, COUNT(*) AS cnt FROM book GROUP BY category";
            $r = mysqli_query($conn, $sql);
            ?>

            <table class="table table-bordered table-sm">
                <thead><tr><th>Category</th><th># Books</th></tr></thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($r)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td><?= $row['cnt']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>




        <?php if ($report == 'neverborrowed') { ?>
            <div class="report-title">🚫 Borrowers Who Never Borrowed or Bought</div>

            <?php
            $sql = "
                SELECT br.borrower_id, br.first_name, br.last_name
                FROM borrower br
                LEFT JOIN loan l ON br.borrower_id = l.borrower_id
                LEFT JOIN sale s ON br.borrower_id = s.borrower_id
                WHERE l.loan_id IS NULL AND s.sale_id IS NULL
            ";
            $r = mysqli_query($conn, $sql);
            ?>

            <table class="table table-bordered table-sm">
                <thead><tr><th>ID</th><th>Name</th></tr></thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($r)) { ?>
                    <tr>
                        <td><?= $row['borrower_id']; ?></td>
                        <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>




        <!-- ********  NEW REPORT #7  ******** -->
        <?php if ($report == 'multiauthor') { ?>
            <div class="report-title">📚 Books with More Than One Author</div>

            <?php
            $sql = "
                SELECT b.book_id,
                       b.title,
                       COUNT(DISTINCT ba.author_id) AS author_count,
                       GROUP_CONCAT(
                           DISTINCT CONCAT(a.first_name,' ',a.last_name)
                           ORDER BY a.last_name,a.first_name
                           SEPARATOR ', '
                       ) AS authors
                FROM book b
                JOIN bookauthor ba ON b.book_id = ba.book_id
                JOIN author a ON ba.author_id = a.author_id
                GROUP BY b.book_id, b.title
                HAVING author_count > 1
                ORDER BY author_count DESC, b.title
            ";
            $r = mysqli_query($conn, $sql);
            ?>

            <?php if (mysqli_num_rows($r) == 0) { ?>
                <div class="no-results">No books with multiple authors found.</div>
            <?php } else { ?>
                <table class="table table-bordered table-sm">
                    <thead><tr><th>ID</th><th>Title</th><th>Author Count</th><th>Authors</th></tr></thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($r)) { ?>
                        <tr>
                            <td><?= $row['book_id']; ?></td>
                            <td><?= htmlspecialchars($row['title']); ?></td>
                            <td class="text-center"><?= $row['author_count']; ?></td>
                            <td><?= htmlspecialchars($row['authors']); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        <?php } ?>
        <!-- ********  END NEW REPORT #7  ******** -->




        <?php if ($report == 'value') { ?>
            <div class="report-title">💵 Total Value of All Books</div>

            <?php
            $r = mysqli_query($conn, "SELECT SUM(original_price) AS total_value FROM book");
            $row = mysqli_fetch_assoc($r);
            ?>

            <h3>Total Value: <?= $row['total_value']; ?></h3>
        <?php } ?>

    </div>
    
<script>

function validateAuthor() {
    const select = document.querySelector("select[name='author_id']");
    if (select && select.value == "0") {
        alert("Please select an author.");
        return false;
    }
    return true;
}


function validateBorrower() {
    const select = document.querySelector("select[name='borrower_id']");
    if (select && select.value == "0") {
        alert("Please select a borrower.");
        return false;
    }
    return true;
}


function validateCountry() {
    const select = document.querySelector("select[name='country']");
    if (select && select.value.trim() === "") {
        alert("Please select a country.");
        return false;
    }
    return true;
}


function validateDateRange() {
    const from = document.querySelector("input[name='from_date']");
    const to   = document.querySelector("input[name='to_date']");

    if (from && to) {
        if (from.value === "" || to.value === "") {
            alert("Please select both start and end dates.");
            return false;
        }
        if (from.value > to.value) {
            alert("Start date cannot be later than end date.");
            return false;
        }
    }
    return true;
}


document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", function(e) {
        const reportType = new URLSearchParams(window.location.search).get("report");

        if (reportType === "author" && !validateAuthor()) e.preventDefault();
        if (reportType === "borrower" && !validateBorrower()) e.preventDefault();
        if (reportType === "country" && !validateCountry()) e.preventDefault();
        if (reportType === "date" && !validateDateRange()) e.preventDefault();
    });
});
</script>


</div>