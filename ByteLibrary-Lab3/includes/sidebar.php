<?php
// ============================================================
//  ByteLibrary — Shared Sidebar
//  File: includes/sidebar.php
//  Usage: include 'includes/sidebar.php'; — pass $activePage before including
//  Example: $activePage = 'dashboard'; include 'includes/sidebar.php';
// ============================================================
if (!isset($activePage)) $activePage = '';
$nav = [
    'dashboard' => ['href' => 'dashboard.php', 'icon' => '◈',  'label' => 'Dashboard'],
    'books'     => ['href' => 'books.php',     'icon' => '📚', 'label' => 'Book List'],
    'add_book'  => ['href' => 'add_book.php',  'icon' => '＋', 'label' => 'Add Book'],
    'borrowers' => ['href' => 'borrowers.php', 'icon' => '👥', 'label' => 'Borrowers'],
    'status'    => ['href' => 'status.php',    'icon' => '⏱',  'label' => 'Loan Status'],
    'erd'       => ['href' => 'erd.php',       'icon' => '🗄',  'label' => 'DB Design'],
];
$avatar = strtoupper(substr($currentUser['display_name'] ?? 'A', 0, 1));
?>
<aside class="sidebar">
  <div class="logo">
    <div class="logo-mark">Byte<span>Library</span></div>
    <div class="logo-sub">Library System</div>
  </div>
  <nav class="nav">
    <div class="nav-section-label">Main</div>
    <?php foreach ($nav as $key => $item): ?>
      <a href="<?= $item['href'] ?>" class="nav-item <?= $activePage === $key ? 'active' : '' ?>">
        <span class="icon"><?= $item['icon'] ?></span> <?= $item['label'] ?>
      </a>
    <?php endforeach; ?>
  </nav>
  <div class="sidebar-user">
    <div class="user-avatar"><?= htmlspecialchars($avatar) ?></div>
    <div class="user-info">
      <div class="user-name"><?= htmlspecialchars($currentUser['display_name'] ?? 'User') ?></div>
      <div class="user-role">Librarian</div>
    </div>
    <a href="logout.php" class="logout-btn" title="Sign out">⏻</a>
  </div>
</aside>
