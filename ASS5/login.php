<?php
session_start();
$currentPage = 'login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - JSTREET</title>
  <link rel="stylesheet" href="css/signup.css">
</head>
<body>

  <!-- Main Content Container --> 
  <div class="main-container">
    <div class="form-container">
      <h2>Login</h2>
      <form id="loginForm" method="POST">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
        <p class="register-link">
            Don't have an account?
            <a href="signup.php">Register here</a>
        </p>
        <div id="error" class="error-message"></div>
        <div id="success" class="success-message"></div>
      </form>
    </div>
  </div>

  <script src="js/login.js"></script>

  <?php include 'php/footer.php'; ?>
</body>
</html>