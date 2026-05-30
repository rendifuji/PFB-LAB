<?php

require_once __DIR__ . '/../aunth/functions.php';

redirect_if_authenticated();

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $errors[] = 'All inputs must not be empty.';
    }

    if ($username !== '' && strlen($username) < 8) {
        $errors[] = 'Username must be at least 8 characters.';
    }

    if ($password !== '' && strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email format is invalid.';
    }

    if (!$errors) {
        $querycheck = $conn->prepare('SELECT userId FROM msuser WHERE email = ? OR username = ?');
        $querycheck->bind_param('ss', $email, $username);
        $querycheck->execute();
        $result = $querycheck->get_result();
        if ($result && $result->num_rows > 0) {
            $errors[] = 'Username or email already exists.';
        }
    }

    if (!$errors) {
        $userId = next_id($conn, 'msuser', 'userId', 'US');
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $credit = 0;
        $role = 'User';
        $query = $conn->prepare('INSERT INTO msuser (userId, username, password, email, credit, role) VALUES (?, ?, ?, ?, ?, ?)');
        $query->bind_param('ssssis', $userId, $username, $hash, $email, $credit, $role);
        $query->execute();
        header('Location: login.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <title>Severos - Register</title>
  </head>
  <body>
    <nav>
      <h2>Severos</h2>
    </nav>
    <main>
      <div class="auth-container">
        <header>
          <h1>Register</h1>
          <p>Sign up. Load up. Stand out.</p>
        </header>
        <div class="credentials">
          <p>Create your account by filling in the information below.</p>
          <?php if ($errors): ?>
            <div style="color: #ff3636; margin-bottom: 1rem;">
              <ul style="margin: 0; padding-left: 1.2rem;">
                <?php foreach ($errors as $error): ?>
                  <li><?= e($error) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <form action="" method="POST">
            <label for="username">Username</label>
            <input
              type="text"
              id="username"
              name="username"
              placeholder="Enter your username (min. 8 characters)"
              value="<?= e($username) ?>"
            />
            <label for="email">Email</label>
            <input
              type="text"
              id="email"
              name="email"
              placeholder="Enter your email"
              value="<?= e($email) ?>"
            />
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password (min. 8 characters)"
            />
            <button type="submit">Register</button>
          </form>
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
      </div>
    </main>
  </body>
</html>
