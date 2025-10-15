<?php
session_start();
require __DIR__ . "/../config/dbconfig.php";

if (!isset($_POST['post_id'], $_POST['title'], $_POST['content'], $_SESSION['user_id'])) {
  header("Location: /news.php");
  exit();
}

$postId = $_POST['post_id'];
$title = $_POST['title'];
$content = $_POST['content'];
$currentUserId = $_SESSION['user_id'];
$editedAt = date("Y-m-d H:i:s");

// Verify ownership
$stmt = $conn->prepare("SELECT author_id FROM posts WHERE post_id = ?");
$stmt->bind_param("i", $postId);
$stmt->execute();
$stmt->bind_result($authorId);
$stmt->fetch();
$stmt->close();

if ($authorId !== $currentUserId) {
  echo "Unauthorized update.";
  exit();
}

// Update post
$stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, edited_at = ? WHERE post_id = ?");
$stmt->bind_param("sssi", $title, $content, $editedAt, $postId);
$stmt->execute();
$stmt->close();

$tab = $_POST['tab'] ?? 'official';
header("Location: /news.php?tab=$tab");
exit();