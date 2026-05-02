<?php
// ============================================================
//  ByteLibrary — Login
//  Lab Activity 3 · Part 5: Read Operation (check user record)
// ============================================================
session_start();
if (!empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connect.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter your username and password.";
    } else {
        // Read Operation: SELECT user record
        $sql    = "SELECT id, display_name, username, password, role FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                unset($user['password']); // don't store hash in session
                $_SESSION['user'] = $user;
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Incorrect username or password.";
            }
        } else {
            $error = "Incorrect username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - ByteLibrary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@300;400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="login-screen">
  <div class="login-left">
    <div class="login-brand">
      <div class="login-brand-name">Byte<span>Library</span></div>
      <div class="login-brand-sub">Library System</div>
    </div>
    <div class="login-headline">Your library,<br><em>organised.</em></div>
    <p class="login-subtext">Manage your entire book collection, borrowers, and loan activity — all from one place.</p>
    <div class="login-deco">ByteLibrary · v1.0 · 2025</div>
  </div>
  <div class="login-right">
    <div class="login-box">
      <div class="login-box-title">Welcome back</div>
      <div class="login-box-sub">Sign in to your library dashboard</div>

      <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success">Account created! You can now sign in.</div>
      <?php endif; ?>

      <!-- Part 3: Form -->
      <form method="POST">
        <div class="login-field">
          <label class="login-label">Username</label>
          <input class="login-input" type="text" name="username"
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                 placeholder="Enter your username" required>
        </div>
        <div class="login-field">
          <label class="login-label">Password</label>
          <input class="login-input" type="password" name="password"
                 placeholder="????????" required>
        </div>
        <button type="submit" class="login-btn">Sign In</button>
      </form>

      <div class="login-switch">Don't have an account? <a href="register.php">Create one</a></div>
    </div>
  </div>
</div>
</body>
</html>
