<?php



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
          <form action="store.php" method="POST">
            <label for="text">Username</label>
            <input
              type="text"
              id="username"
              name="username"
              placeholder="Enter your username (min. 8 characters)"
            />
            <label for="email">Email</label>
            <input
              type="text"
              id="email"
              name="email"
              placeholder="Enter your email"
            />
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password (min. 8 characters)"
            />
            <button type="submit" value="save" name="save">Register</button>
          </form>
        </div>
        <p>Already have an account? <a href="login.html">Login here</a>.</p>
      </div>
    </main>
  </body>
</html>



test ganti