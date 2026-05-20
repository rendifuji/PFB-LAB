<?php 

require_once __DIR__ . '/../aunth/functions.php';

redirect_if_authenticated();

$errors = [];
$email = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if($email === '' || $password === ''){
        $errors[] = 'email and password must not be empty';
    }

    if($password !== '' && strlen($password) < 8){
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (!$errors) {
        $query = $conn->prepare('SELECT userId, username, password, role, credit FROM msuser WHERE email = ?');
        $query->bind_param('s', $email);
        $query->execute();
        $user = $query->get_result()->fetch_assoc();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Invalid email or password.';
        } else {
            set_login_cookies($user);
            header('Location: ' . (str_starts_with(strtolower(trim($user['role'])), 'admin') ? '../admin/dashboard.php' : '../user/index.php'));
            exit;
        }
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <title>Severos - Login</title>
  </head>
  <body>
    <nav>
      <h2>Severos</h2>
    </nav>
    <main>
      <div class="auth-container">
        <header>
          <h1>Login</h1>
          <p>The market's hot. Your next weapon awaits</p>
        </header>
        <div class="credentials">
          <p>Please enter your credentials to login</p>
          <?php if($errors): ?>
            <p style="color: #db3a3a;"><?= e(implode('', $errors)) ?></p>
          <?php endif; ?>
          <form action="" method="POST">
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
            <button type="submit">Login</button>
          </form>
        </div>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
      </div>
    </main>
  </body>
</html>
