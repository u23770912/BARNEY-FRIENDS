<?php
session_start();
$currentPage = 'signup.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - JSTREET</title>
  <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <header>
    <div class="header-links">
      <a href="login.php">Login</a>
    </div>
  </header>

  <!-- Main Content Container -->
  <div class="main-container">
    <div class="form-container">
      <h2>Create an Account</h2>
      <form id="signupForm" method="POST">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>

        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="user_type">User Type</label>
          <select id="user_type" name="user_type" required>
            <option value="">-- Select User Type --</option>
            <option value="Customer">Customer</option>
            <option value="Courier">Courier</option>
          </select>


        <button type="submit">Register</button>
        <div id="error" class="error-message"></div>
        <div id="success" class="success-message"></div>
      </form>
    </div>
  </div>

  <script src="js/signup.js"></script>

  <?php include 'php/footer.php'; ?>
</body>
</html>