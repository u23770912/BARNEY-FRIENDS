<?php
  // header.php
  session_start();
  include_once 'config.php';

  // Figure out which CSS to load (e.g. index.php → css/index.css)
  $currentPage = basename($_SERVER['PHP_SELF']);
  $cssFile     = 'css/' . pathinfo($currentPage, PATHINFO_FILENAME) . '.css';
?>
<link rel="stylesheet" href="<?= htmlspecialchars($cssFile) ?>">

<head>
  <div class="header-text">JSTREET</div>

  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <nav id="navLinks" class="header-links">
    <a href="../../index.html" class="<?= $currentPage==='index.html'    ? 'active':'' ?>">Home</a>
    <!-- by default, we’ll show Login/Register; JS will swap in Welcome+Logout -->
    <a href="../login.php"    id="loginLink">Login</a>
    <a href="../signup.php"   id="registerLink">Sign Up</a-->
    <!-- placeholders: -->
    <span id="userNameDisplay" style="display:none;"></span>
    <a href="#" id="logoutLink" style="display:none;">Logout</a>
  </nav>
  <script src="js/main.js" defer></script>
</head>

<script>
  (function(){
    const nav         = document.getElementById('navLinks');
    const userName    = sessionStorage.getItem('userName');
    const loginLink   = document.getElementById('loginLink');
    const regLink     = document.getElementById('registerLink');
    const nameSpan    = document.getElementById('userNameDisplay');
    const logoutLink  = document.getElementById('logoutLink');

    if (userName) {
      // hide the login/register links
      loginLink.style.display = 'none';
      regLink.style.display   = 'none';

      // show welcome + logout
      nameSpan.textContent    = `Welcome, ${userName}`;
      nameSpan.style.display  = 'inline-block';
      logoutLink.style.display = 'inline-block';

      logoutLink.addEventListener('click', function(e) {
        e.preventDefault();
        sessionStorage.clear();             // clear stored apiKey + userName
        window.location.href = '../login.php'; // redirect to login
      });
    }
  })();
</script>
