<?php

require __DIR__ . "/assets/config/dbconfig.php";

session_start();

if (isset($_SESSION['show_welcome']) && $_SESSION['show_welcome'] === true) {
  $departmentCode = $_SESSION['department_code'] ?? 'default';}

if(isset($_SESSION['user_id'])){
  header("Location: home.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>U-Plug Login / Sign Up</title>
  <link rel="stylesheet" href="/assets/css/index.css">
  <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
  <script src="/assets/javascript/validation.js" defer></script>

</head>
<body>

    <div id="welcome-screen">
      <div class="welcome-message">
        <img src="/assets/images/client/UplugLogo.png" alt="Uplug Logo"> <br>
        <?php if (isset($_SESSION['department_code'])): ?>
          <img src="/assets/images/client/department/<?= htmlspecialchars($_SESSION['department_code']) ?>.png" alt="<?= htmlspecialchars($_SESSION['department_code']) ?> Logo">
        <?php endif; ?>
        <img id="loading" src="/assets/images/client/Loading.gif" alt="Loading">
      </div>
    </div>

  <div class="auth-container">


    <div class="glass-card" id="login-card">

      <h2>Login</h2>

      <form action="/assets/server/login-process.php" class="auth-form" id="login-form" autocomplete="off" method="POST" novalidate>
        <select name="role" id="role" required>
          <option value="" disabled selected>Select Account Type</option>
          <option value="student">Student</option>
          <option value="faculty">Faculty</option>
        </select>
        <input type="text" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit" class="login-btn">Login</button>
      </form>

      <div class="switch-link">
        <span>Don't have an account?</span>
        <button id="show-signup" type="button">Sign Up</button>
      </div>

    </div>

    <div class="glass-card" id="signup-card" style="display:none;">

      <h2>Sign Up</h2>

      <form action="/assets/server/signup-process.php" class="auth-form" id="signup-form" autocomplete="off" method="POST" novalidate>
        <select name="role" required>
          <option value="" disabled selected>Select Account Type</option>
          <option value="student">Student</option>
          <option value="faculty">Faculty</option>
        </select>
        <select name="department" required>
          <option value="" disabled selected>Select Department</option>
          <option value="SHS">SHS - Senior Highschool</option>
          <option value="CITE">CITE - College of Information Technology Education</option>
          <option value="CCJE">CCJE - College of Criminal Justice Education</option>
          <option value="CAHS">CAHS - College of Allied Health Sciences</option>
          <option value="CAS">CAS - College of Arts and Sciences</option>
          <option value="CEA">CEA - College of Engineering and Architecture</option>
          <option value="CELA">CELA - College of Education and Liberal Arts</option>
          <option value="CMA">CMA - College of Management and Accountancy</option>
          <option value="COL">COL - College of Law</option>
        </select>
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        <button type="submit" class="signup-btn">Sign Up</button>
      </form>

      <div class="switch-link">
        <span>Already have an account?</span>
        <button id="show-login" type="button">Login</button>
      </div>

    </div>

  </div>

  <!---FOR JS--->
  
  <script>
    // Toggle between login and signup
    document.getElementById('show-signup').onclick = function() {
      document.getElementById('login-card').style.display = 'none';
      document.getElementById('signup-card').style.display = 'flex';
    };
    document.getElementById('show-login').onclick = function() {
    document.getElementById('login-card').style.display = 'flex';
    document.getElementById('signup-card').style.display = 'none';
    };
  </script>
</body>
</html>