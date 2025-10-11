<?php
session_start();
require __DIR__ . "/../config/dbconfig.php";

if (!isset($_POST['post_id'], $_SESSION['user_id'])) {
  header("Location: /news.php");
  exit();
}

$postId = $_POST['post_id'];
$currentUserId = $_SESSION['user_id'];
$tab = $_POST['tab'] ?? 'official';

// Verify ownership
$stmt = $conn->prepare("SELECT author_id FROM posts WHERE post_id = ?");
$stmt->bind_param("i", $postId);
$stmt->execute();
$stmt->bind_result($authorId);
$stmt->fetch();
$stmt->close();

if ($authorId !== $currentUserId) {
  echo "Unauthorized deletion.";
  exit();
}

// Delete post
$stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
$stmt->bind_param("i", $postId);
$stmt->execute();
$stmt->close();

header("Location: /news.php?tab=$tab");
exit();