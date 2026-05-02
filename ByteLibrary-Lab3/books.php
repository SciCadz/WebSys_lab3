<?php
// ============================================================
//  ByteLibrary — Book List
//  Lab Activity 3 · Part 5: Display Data (Read Operation)
// ============================================================
require 'includes/auth.php';
include 'db_connect.php';
$activePage = 'books';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM books WHERE id = $id");
    header('Location: books.php?msg=deleted');
    exit;
}

// Search / filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

$where = [];
if (!empty($search)) {
    $s = $conn->real_escape_string($search);
    $where[] = "(title LIKE '%$s%' OR author LIKE '%$s%' OR isbn LIKE '%$s%')";
}
if (!empty($status) && in_array($status, ['Available','Borrowed','Overdue'])) {
    $where[] = "status = '" . $conn->real_escape_string($status) . "'";
}
$whereSQL = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Part 5: Read Operation ────────────────────────────────────
$sql    = "SELECT * FROM books $whereSQL ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book List - ByteLibrary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@300;400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="app">
  <?php include 'includes/sidebar.php'; ?>
  <main class="main">
    <header class="topbar">
      <div class="page-title">Book List</div>
      <div class="topbar-actions">
        <a href="add_book.php" class="btn btn-primary btn-sm">+ Add Book</a>
      </div>
    </header>
    <div class="content">

      <?php if (isset($_GET['msg'])): ?>
        <div class="alert <?= $_GET['msg'] === 'added' ? 'alert-success' : 'alert-error' ?>">
          <?= $_GET['msg'] === 'added' ? 'Book added successfully!' : 'Book removed from collection.' ?>
        </div>
      <?php endif; ?>

      <div class="table-wrap">
        <div class="table-toolbar">
          <form method="GET" style="display:contents;">
            <div class="search-wrap">
              <input class="search-input" name="search" placeholder="Search by title, author, ISBN..."
                     value="<?= htmlspecialchars($search) ?>">
            </div>
            <select class="form-select" name="status" style="width:160px;padding:7px 12px;" onchange="this.form.submit()">
              <option value="">All Status</option>
              <?php foreach (['Available','Borrowed','Overdue'] as $opt): ?>
                <option <?= $status === $opt ? 'selected' : '' ?>><?= $opt ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-ghost btn-sm">Search</button>
          </form>
        </div>

        <table>
          <thead>
            <tr>
              <th>ISBN</th>
              <th>Title</th>
              <th>Author</th>
              <th>Genre</th>
              <th>Year</th>
              <th>Copies</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Part 5: Display records
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $badgeClass = '';
                    if ($row['status'] === 'Available') $badgeClass = 'badge-available';
                    elseif ($row['status'] === 'Borrowed') $badgeClass = 'badge-borrowed';
                    elseif ($row['status'] === 'Overdue')  $badgeClass = 'badge-overdue';
            ?>
            <tr>
              <td><span style="font-family:'DM Mono',monospace;font-size:11px;color:var(--muted)"><?= htmlspecialchars($row['isbn'] ?? 'N/A') ?></span></td>
              <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
              <td><?= htmlspecialchars($row['author']) ?></td>
              <td><?= htmlspecialchars($row['genre']) ?></td>
              <td><?= $row['year_published'] ?></td>
              <td><?= $row['copies'] ?></td>
              <td><span class="badge <?= $badgeClass ?>"><?= $row['status'] ?></span></td>
              <td>
                <div class="action-btns">
                  <a href="books.php?delete=<?= $row['id'] ?>"
                     class="btn btn-ghost btn-sm"
                     style="color:var(--accent)"
                     onclick="return confirm('Remove this book?')">x Delete</a>
                </div>
              </td>
            </tr>
            <?php
                } // end while
            } else {
                echo '<tr><td colspan="8" class="empty-cell">No books found.</td></tr>';
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
