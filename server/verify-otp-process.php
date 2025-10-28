<?php
session_start();
require __DIR__ . '/../config/dbconfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputOtp = $_POST['otp_input'] ?? '';
    $sessionOtp = $_SESSION['otp_code'] ?? null;
    $expiry = $_SESSION['otp_expiry'] ?? 0;

    if (!$sessionOtp || time() > $expiry) {
        echo "OTP expired. Please request a new one.";
        exit();
    }

    if ($inputOtp != $sessionOtp) {
        echo "Incorrect OTP.";
        exit();
    }

    // Mark user as verified
    $email = $_SESSION['pending_email'];
    $table = $_SESSION['pending_table'];
    $sql = "UPDATE $table SET verified = 1 WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Clear session and force re-login
    session_unset();                    // remove all session variables
    session_destroy();                  // destroy the session
    header("Location: ../../index.php");
    exit();
}
?>
