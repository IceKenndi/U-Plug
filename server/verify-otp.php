<?php
session_start();
if (!isset($_SESSION['pending_email'])) {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Verify OTP</title>
  <link rel="stylesheet" href="/assets/css/index.css">
</head>
<body>
  <div class="glass-card">
    <h2>Verify Your Email</h2>
    <form action="verify-otp-process.php" method="POST">
      <input type="text" name="otp_input" placeholder="Enter OTP" required>
      <button type="submit">Verify</button>
    </form>

    <?php if (time() > $_SESSION['otp_expiry']): ?>
      <form action="resend-otp.php" method="POST">
        <button type="submit">Resend OTP</button>
      </form>
    <?php else: ?>
      <p>You can resend OTP after <?= ($_SESSION['otp_expiry'] - time()) ?> seconds.</p>
    <?php endif; ?>
  </div>
</body>
</html>
