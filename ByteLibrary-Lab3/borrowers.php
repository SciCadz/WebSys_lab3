<?php
// ============================================================
//  ByteLibrary — Borrowers
//  Lab Activity 3 · Part 3 & 4: Create Form + Insert
//                  Part 5: Read (display borrowers)
// ============================================================
require 'includes/auth.php';
include 'db_connect.php';
$activePage = 'borrowers';

$error   = '';
$success = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM borrowers WHERE id = $id");
    header('Location: borrowers.php?msg=deleted');
    exit;
}

// Handle toggle status
if (isset($_GET['toggle'])) {
    $id  = (int)$_GET['toggle'];
    $cur = $conn->query("SELECT status FROM borrowers WHERE id = $id")->fetch_assoc()['status'];
    $new = ($cur === 'Active') ? 'Suspended' : 'Active';
    $conn->query("UPDATE borrowers SET status='$new' WHERE id=$id");
    header('Location: borrowers.php');
    exit;
}

// Part 4: Insert new borrower
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($name) || empty($email)) {
        $error = "Name and email are required.";
    } else {
        // Read: check duplicate email
        $check = $conn->query("SELECT id FROM borrowers WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $error = "A borrower with that email already exists.";
        } else {
            $sql = "INSERT INTO borrowers (name, email, phone) VALUES ('$name', '$email', '$phone')";
            if ($conn->query($sql) === TRUE) {
                $success = "Record inserted successfully. $name has been registered.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}

// Part 5: Read — display all borrowers
$search   = isset($_GET['search']) ? $_GET['search'] : '';
$whereSQL = '';
if (!empty($search)) {
    $s = $conn->real_escape_string($search);
    $whereSQL = "WHERE name LIKE '%$s%' OR email LIKE '%$s%'";
}
$sql_borrowers = "SELECT * FROM borrowers $whereSQL ORDER BY created_at DESC";
$result        = $conn->query($sql_borrowers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Borrowers - ByteLibrary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@300;400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .modal-overlay { position:fixed;inset:0;background:rgba(26,23,20,.5);z-index:200;display:none;align-items:center;justify-content:center;backdrop-filter:blur(2px); }
    .modal-overlay.open { display:flex; }
    .modal { background:var(--card);border:1px solid var(--border);border-radius:12px;padding:28px 32px;width:460px;max-width:95vw; }
    .modal-title { font-family:'DM Serif Display',serif;font-size:20px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center; }
    .modal-close { background:none;border:none;cursor:pointer;color:var(--muted);font-size:18px; }
  </style>
</head>
<body>
<div id="app">
  <?php include 'includes/sidebar.php'; ?>
  <main class="main">
    <header class="topbar">
      <div class="page-title">Borrower Directory</div>
      <div class="topbar-actions">
        <button class="btn btn-primary" onclick="document.getElementById('add-modal').classList.add('open')">+ Register Borrower</button>
      </div>
    </header>
    <div class="content">

      <?php if ($error):   ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
      <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-error">Borrower removed.</div>
      <?php endif; ?>

      <div class="table-wrap">
        <div class="table-toolbar">
          <form method="GET" style="display:contents;">
            <div class="search-wrap">
              <input class="search-input" name="search" placeholder="Search by name, email..."
                     value="<?= htmlspecialchars($search) ?>">
            </div>
            <button type="submit" class="btn btn-ghost btn-sm">Search</button>
          </form>
        </div>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $badgeClass = ($row['status'] === 'Active') ? 'badge-active' : 'badge-suspended';
            ?>
            <tr>
              <td><span style="font-family:'DM Mono',monospace;font-size:11px;color:var(--muted)">BR-<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></span></td>
              <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['phone'] ?? '—') ?></td>
              <td><span class="badge <?= $badgeClass ?>"><?= $row['status'] ?></span></td>
              <td>
                <div class="action-btns">
                  <a href="borrowers.php?toggle=<?= $row['id'] ?>" class="btn btn-ghost btn-sm">
                    <?= $row['status'] === 'Active' ? 'Suspend' : 'Activate' ?>
                  </a>
                  <a href="borrowers.php?delete=<?= $row['id'] ?>"
                     class="btn btn-ghost btn-sm" style="color:var(--accent)"
                     onclick="return confirm('Remove this borrower?')">x</a>
                </div>
              </td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6" class="empty-cell">No borrowers found.</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<!-- Part 3: Register Borrower Form (Modal) -->
<div class="modal-overlay" id="add-modal">
  <div class="modal">
    <div class="modal-title">
      Register Borrower
      <button class="modal-close" onclick="document.getElementById('add-modal').classList.remove('open')">x</button>
    </div>
    <form method="POST">
      <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Full Name</label>
        <input class="form-input" type="text" name="name" placeholder="Juan Dela Cruz" required>
      </div>
      <div class="form-group" style="margin-bottom:14px;">
        <label class="form-label">Email Address</label>
        <input class="form-input" type="email" name="email" placeholder="juan@email.com" required>
      </div>
      <div class="form-group" style="margin-bottom:20px;">
        <label class="form-label">Phone Number</label>
        <input class="form-input" type="text" name="phone" placeholder="0917-123-4567">
      </div>
      <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary">Add Borrower</button>
        <button type="button" class="btn btn-ghost" onclick="document.getElementById('add-modal').classList.remove('open')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<?php if ($error && $_SERVER["REQUEST_METHOD"] == "POST"): ?>
<script>document.getElementById('add-modal').classList.add('open');</script>
<?php endif; ?>

</body>
</html>
