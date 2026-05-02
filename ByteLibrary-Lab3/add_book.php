<?php
// ============================================================
//  ByteLibrary — Add Book
//  Lab Activity 3 · Part 3: Create Form  |  Part 4: Insert Data
// ============================================================
require 'includes/auth.php';
include 'db_connect.php';
$activePage = 'add_book';

$error   = '';
$success = '';

// ── Part 4: Insert Data (Create Operation) ───────────────────
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $isbn           = $_POST['isbn'];
    $title          = $_POST['title'];
    $author         = $_POST['author'];
    $genre          = $_POST['genre'];
    $publisher      = $_POST['publisher'];
    $year_published = $_POST['year_published'];
    $copies         = $_POST['copies'];
    $description    = $_POST['description'];

    // Basic validation
    if (empty($title) || empty($author)) {
        $error = "Title and Author are required.";
    } else {
        // Check duplicate ISBN
        if (!empty($isbn)) {
            $check = $conn->query("SELECT id FROM books WHERE isbn = '$isbn'");
            if ($check->num_rows > 0) {
                $error = "A book with that ISBN already exists.";
            }
        }

        if (empty($error)) {
            $sql = "INSERT INTO books (isbn, title, author, genre, publisher, year_published, copies, description)
                    VALUES ('$isbn', '$title', '$author', '$genre', '$publisher', '$year_published', '$copies', '$description')";

            if ($conn->query($sql) === TRUE) {
                $success = "Record inserted successfully. \"$title\" has been added to the collection.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Book - ByteLibrary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@300;400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="app">
  <?php include 'includes/sidebar.php'; ?>
  <main class="main">
    <header class="topbar">
      <div class="page-title">Add Book</div>
      <div class="topbar-actions">
        <a href="books.php" class="btn btn-ghost btn-sm">← Book List</a>
      </div>
    </header>
    <div class="content">
      <div class="form-card">
        <div style="margin-bottom:24px;">
          <div style="font-family:'DM Serif Display',serif;font-size:22px;margin-bottom:4px;">Add New Book</div>
          <div style="color:var(--muted);font-size:13px;">Fill in the details to add a book to the collection.</div>
        </div>

        <?php if ($error):   ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="books.php" style="color:var(--sage);font-weight:600;">View Book List →</a></div><?php endif; ?>

        <!-- Part 3: Create Form -->
        <form method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">ISBN</label>
              <input class="form-input" type="text" name="isbn" placeholder="978-0-00-000000-0"
                     value="<?= htmlspecialchars($_POST['isbn'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Year Published</label>
              <input class="form-input" type="number" name="year_published" placeholder="2024" min="1800" max="2099"
                     value="<?= htmlspecialchars($_POST['year_published'] ?? '') ?>">
            </div>
            <div class="form-group full">
              <label class="form-label">Book Title <span style="color:var(--accent)">*</span></label>
              <input class="form-input" type="text" name="title" placeholder="Enter full title" required
                     value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Author <span style="color:var(--accent)">*</span></label>
              <input class="form-input" type="text" name="author" placeholder="First Last" required
                     value="<?= htmlspecialchars($_POST['author'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Genre</label>
              <select class="form-select" name="genre">
                <option value="">Select genre</option>
                <?php foreach (['Fiction','Non-Fiction','Science','History','Technology','Philosophy','Biography','Poetry','Other'] as $g): ?>
                  <option <?= (($_POST['genre'] ?? '') === $g) ? 'selected' : '' ?>><?= $g ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Publisher</label>
              <input class="form-input" type="text" name="publisher" placeholder="Publisher name"
                     value="<?= htmlspecialchars($_POST['publisher'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Copies</label>
              <input class="form-input" type="number" name="copies" min="1"
                     value="<?= (int)($_POST['copies'] ?? 1) ?>">
            </div>
            <div class="form-group full">
              <label class="form-label">Description</label>
              <textarea class="form-textarea" name="description" placeholder="Short description or synopsis..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">+ Add to Collection</button>
            <button type="reset"  class="btn btn-ghost">Clear</button>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>
</body>
</html>
