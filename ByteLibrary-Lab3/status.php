<?php
// ============================================================
//  ByteLibrary — Loan Status
//  Lab Activity 3 · Part 5: Read Operation (loans joined with books & borrowers)
// ============================================================
require 'includes/auth.php';
include 'db_connect.php';
$activePage = 'status';

// Part 5: Read — stats
$total_loans    = $conn->query("SELECT COUNT(*) AS n FROM loans")->fetch_assoc()['n'];
$active_loans   = $conn->query("SELECT COUNT(*) AS n FROM loans WHERE status='Active'")->fetch_assoc()['n'];
$returned_loans = $conn->query("SELECT COUNT(*) AS n FROM loans WHERE status='Returned'")->fetch_assoc()['n'];
$overdue_loans  = $conn->query("SELECT COUNT(*) AS n FROM loans WHERE status='Overdue'")->fetch_assoc()['n'];

// Part 5: Read — overdue records
$sql_overdue = "SELECT b.title, br.name AS borrower, l.due_date,
                DATEDIFF(CURDATE(), l.due_date) AS days_late, l.penalty
                FROM loans l
                JOIN books b ON l.book_id = b.id
                JOIN borrowers br ON l.borrower_id = br.id
                WHERE l.status = 'Overdue'
                ORDER BY l.due_date ASC";
$result_overdue = $conn->query($sql_overdue);

// Part 5: Read — active loan records
$sql_active = "SELECT b.title, br.name AS borrower, l.issue_date, l.due_date
               FROM loans l
               JOIN books b ON l.book_id = b.id
               JOIN borrowers br ON l.borrower_id = br.id
               WHERE l.status = 'Active'
               ORDER BY l.due_date ASC";
$result_active = $conn->query($sql_active);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Status - ByteLibrary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@300;400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="app">
  <?php include 'includes/sidebar.php'; ?>
  <main class="main">
    <header class="topbar">
      <div class="page-title">Active Loans &amp; Overdue</div>
      <div class="topbar-actions">
        <a href="books.php" class="btn btn-ghost btn-sm">View Books</a>
      </div>
    </header>
    <div class="content">

      <div class="stats-strip">
        <div class="stat-card s1"><div class="stat-label">Total Loans</div><div class="stat-value"><?= $total_loans ?></div><div class="stat-sub">All time</div></div>
        <div class="stat-card s2"><div class="stat-label">Active</div><div class="stat-value"><?= $active_loans ?></div><div class="stat-sub">Currently out</div></div>
        <div class="stat-card s3"><div class="stat-label">Returned</div><div class="stat-value"><?= $returned_loans ?></div><div class="stat-sub">Completed</div></div>
        <div class="stat-card s4"><div class="stat-label">Overdue</div><div class="stat-value"><?= $overdue_loans ?></div><div class="stat-sub">Needs follow-up</div></div>
      </div>

      <div class="status-grid">
        <!-- Overdue panel -->
        <div class="status-panel">
          <div class="status-panel-header">
            <div class="status-panel-title">Overdue Books</div>
            <span class="badge badge-overdue"><?= $overdue_loans ?></span>
          </div>
          <table>
            <thead><tr><th>Title</th><th>Borrower</th><th>Days Late</th><th>Penalty</th></tr></thead>
            <tbody>
              <?php
              if ($result_overdue->num_rows > 0) {
                  while ($row = $result_overdue->fetch_assoc()) {
              ?>
              <tr>
                <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                <td><?= htmlspecialchars($row['borrower']) ?></td>
                <td style="color:var(--accent);font-weight:600">+<?= $row['days_late'] ?>d</td>
                <td>P<?= number_format($row['penalty'], 2) ?></td>
              </tr>
              <?php
                  }
              } else {
                  echo '<tr><td colspan="4" class="empty-cell">No overdue books</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>

        <!-- Active loans panel -->
        <div class="status-panel">
          <div class="status-panel-header">
            <div class="status-panel-title">Active Loans</div>
            <span class="badge badge-active"><?= $active_loans ?></span>
          </div>
          <table>
            <thead><tr><th>Title</th><th>Borrower</th><th>Due Date</th></tr></thead>
            <tbody>
              <?php
              if ($result_active->num_rows > 0) {
                  while ($row = $result_active->fetch_assoc()) {
              ?>
              <tr>
                <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                <td><?= htmlspecialchars($row['borrower']) ?></td>
                <td><?= $row['due_date'] ?></td>
              </tr>
              <?php
                  }
              } else {
                  echo '<tr><td colspan="3" class="empty-cell">No active loans</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>
</div>
</body>
</html>
