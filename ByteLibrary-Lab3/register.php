<?php
// ============================================================
//  ByteLibrary — Register
//  Lab Activity 3 · Part 3: Create Form  |  Part 4: Insert Data
// ============================================================
session_start();
if (!empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connect.php';

    $display_name     = $_POST['display_name'];
    $username         = $_POST['username'];
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($display_name) || empty($username) || empty($password)) {
        $error = "All fields are required.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Read Operation: check if username already exists
        $sql_check = "SELECT id FROM users WHERE username = '$username'";
        $check     = $conn->query($sql_check);

        if ($check->num_rows > 0) {
            $error = "Username \"$username\" is already taken. Choose another.";
        } else {
            // Create Operation: INSERT new user
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $sql    = "INSERT INTO users (display_name, username, password) VALUES ('$display_name', '$username', '$hashed')";

            if ($conn->query($sql) === TRUE) {
                $success = "Account created successfully!";
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
  <title>Register - ByteLibrary</title>
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
    <div class="login-headline">Join<br><em>ByteLibrary.</em></div>
    <p class="login-subtext">Create a staff account to start managing your library's books, borrowers, and loan records.</p>
    <div class="login-deco">ByteLibrary · v1.0 · 2025</div>
  </div>
  <div class="login-right">
    <div class="login-box">
      <div class="login-box-title">Create account</div>
      <div class="login-box-sub">Set up your ByteLibrary staff account</div>

      <?php if ($error):   ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if ($success): ?>
        <div class="alert alert-success">
          <?= htmlspecialchars($success) ?>
          <a href="login.php" style="color:var(--sage);font-weight:600;"> Sign in now</a>
        </div>
      <?php endif; ?>

      <!-- Part 3: Create Form -->
      <form method="POST">
        <div class="login-field">
          <label class="login-label">Display Name</label>
          <input class="login-input" type="text" name="display_name"
                 value="<?= htmlspecialchars($_POST['display_name'] ?? '') ?>"
                 placeholder="e.g. Main Library" required>
        </div>
        <div class="login-field">
          <label class="login-label">Username</label>
          <input class="login-input" type="text" name="username"
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                 placeholder="Choose a username" required>
        </div>
        <div class="login-field">
          <label class="login-label">Password</label>
          <input class="login-input" type="password" name="password"
                 placeholder="At least 6 characters" required>
        </div>
        <div class="pw-rules">Min. 6 characters</div>
        <div class="login-field">
          <label class="login-label">Confirm Password</label>
          <input class="login-input" type="password" name="confirm_password"
                 placeholder="Repeat password" required>
        </div>
        <button type="submit" class="login-btn">Create Account</button>
      </form>

      <div class="login-switch">Already have an account? <a href="login.php">Sign in</a></div>
    </div>
  </div>
</div>
</body>
</html>
