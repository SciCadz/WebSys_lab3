<?php
// ============================================================
//  ByteLibrary — Dashboard
//  Lab Activity 3 · Part 5: Read Operation (stats + recent loans)
// ============================================================
require 'includes/auth.php';
include 'db_connect.php';
$activePage = 'dashboard';

// ── Part 5: Read Operation — Stats ───────────────────────────
$sql_total     = "SELECT COUNT(*) AS n FROM books";
$sql_borrowed  = "SELECT COUNT(*) AS n FROM books WHERE status='Borrowed'";
$sql_overdue   = "SELECT COUNT(*) AS n FROM books WHERE status='Overdue'";

$result_total    = $conn->query($sql_total);
$result_borrowed = $conn->query($sql_borrowed);
$result_overdue  = $conn->query($sql_overdue);

$total    = $result_total->fetch_assoc()['n'];
$borrowed = $result_borrowed->fetch_assoc()['n'];
$overdue  = $result_overdue->fetch_assoc()['n'];
$available = $total - $borrowed - $overdue;

// ── Recent Loans ─────────────────────────────────────────────
$sql_recent = "SELECT b.title, br.name AS borrower, l.issue_date, l.due_date, l.status
               FROM loans l
               JOIN books b ON l.book_id = b.id
               JOIN borrowers br ON l.borrower_id = br.id
               ORDER BY l.created_at DESC
               LIMIT 5";
$result_recent = $conn->query($sql_recent);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - ByteLibrary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@300;400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="app">
  <?php include 'includes/sidebar.php'; ?>
  <main class="main">
    <header class="topbar">
      <div class="page-title">Dashboard</div>
      <div class="topbar-actions">
        <a href="add_book.php" class="btn btn-ghost btn-sm">+ Add Book</a>
      </div>
    </header>
    <div class="content">

      <div class="stats-strip">
        <div class="stat-card s1"><div class="stat-label">Total Books</div><div class="stat-value"><?= $total ?></div><div class="stat-sub">In collection</div></div>
        <div class="stat-card s2"><div class="stat-label">Borrowed</div><div class="stat-value"><?= $borrowed ?></div><div class="stat-sub">Currently out</div></div>
        <div class="stat-card s3"><div class="stat-label">Available</div><div class="stat-value"><?= $available ?></div><div class="stat-sub">Ready to issue</div></div>
        <div class="stat-card s4"><div class="stat-label">Overdue</div><div class="stat-value"><?= $overdue ?></div><div class="stat-sub">Past due date</div></div>
      </div>

      <div class="section-header"><div class="section-title">Recent Loan Activity</div></div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Book Title</th>
              <th>Borrower</th>
              <th>Issue Date</th>
              <th>Due Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result_recent->num_rows > 0) {
                while ($row = $result_recent->fetch_assoc()) {
                    $badgeClass = '';
                    if ($row['status'] === 'Active')    $badgeClass = 'badge-borrowed';
                    elseif ($row['status'] === 'Overdue')  $badgeClass = 'badge-overdue';
                    elseif ($row['status'] === 'Returned') $badgeClass = 'badge-available';
            ?>
            <tr>
              <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
              <td><?= htmlspecialchars($row['borrower']) ?></td>
              <td><?= $row['issue_date'] ?></td>
              <td><?= $row['due_date'] ?></td>
              <td><span class="badge <?= $badgeClass ?>"><?= $row['status'] ?></span></td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="5" class="empty-cell">No loan activity yet.</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>

    </div>
  </main>
</div>
</body>
</html>
