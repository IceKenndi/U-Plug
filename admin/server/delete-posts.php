<?php
session_start();

require_once "../../assets/config/dbconfig.php"; // adjust the path if needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validate POST data
    if (!isset($_POST["id"]) || empty($_POST["id"])) {
        header("Location: /admin/posts.php");
        exit;
    }

    $post_id = $_POST["id"];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
    $stmt->bind_param("s", $post_id);

    if ($stmt->execute()) {
        header("Location: /admin/posts.php");
    } else {
        header("Location: /admin/posts.php");
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: /admin/posts.php");
    exit();
}
?>
