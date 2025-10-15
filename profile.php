<?php

session_start();

if (isset($_SESSION['user_id'])){
    
  require __DIR__ . "/assets/config/dbconfig.php";

  $session_id = $_SESSION['user_id'];

  if (strpos($session_id, 'FAC-') === 0){

    $sql = "SELECT * FROM faculty_users WHERE faculty_id = '$session_id'";

    $result = $conn->query($sql);

    $user = $result->fetch_assoc();
  } else if (strpos($session_id, 'STU-') === 0){

    $sql = "SELECT * FROM student_users WHERE student_id = '$session_id'";

    $result = $conn->query($sql);

    $user = $result->fetch_assoc();
  }

} else {
  header("Location: index.php");
}

?>

<!-- Post Fetching Section -->
<?php
$posts = [];
$personalPosts = [];

$sql = "SELECT * FROM posts ORDER BY COALESCE(edited_at, create_date) DESC";
$result = $conn->query($sql);

while ($post = $result->fetch_assoc()){
  $authorId = $post['author_id'];

  if (strpos($authorId, 'STU-') === 0){
    $stmt = $conn->prepare("SELECT full_name, department FROM student_users WHERE student_id = ?");
  } else if (strpos($authorId, 'FAC-') === 0){
    $stmt = $conn->prepare("SELECT full_name, department FROM faculty_users WHERE faculty_id = ?");
  } else {
    $post['authorName'] = 'Unknown';
    $post['authorRole'] = 'Unknown';
    $post['authorDept'] = 'Unknown';
    continue;
  }

  $stmt->bind_param("s", $authorId);
  $stmt->execute();
  $stmt->bind_result($authorName, $authorDept);
  $stmt->fetch();
  $stmt->close();

  $post['authorName'] = $authorName;
  $post['authorRole'] = strpos($authorId, 'STU-') === 0 ? 'Student' : 'Faculty';
  $post['authorDept'] = $authorDept;

    if ($post['post_type'] === 'personal'){
      $post['post_type'] = 'Personal';
    } else if ($post['post_type'] === 'official'){
      $post['post_type'] = 'Official';
    } else if ($post['post_type'] === 'department'){
      $post['post_type'] = 'Department';
    } else {
      $post['post_type'] = 'Unknown';
    }

  if ($post['author_id'] === $session_id){
      $personalPosts[] = $post;
  }
}
?>
<!-- Post Fetching Section -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile</title>
  <link rel="stylesheet" href="/assets/css/profile.css">
</head>
<body>

<div id="newPostModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <h3>Create New Post</h3>
    <form method="POST" action="/assets/server/create-post.php" autocomplete="off">
      <input type="hidden" name="post_type" value="personal">

      <label for="post_title">Title:</label>
      <textarea id="title" name="title" rows="1" required></textarea>

      <label for="post_content">Content:</label>
      <textarea id="content" name="content" rows="4" required></textarea>

      <button type="submit" name="submit_post">Post</button>
    </form>
  </div>
</div>

  <nav class="navbar">
    <div class="navbar-center">
      <div class="logo">U-Plug</div>
      <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="news.php">News</a>
        <a href="map.php">Map</a>
        <a href="messaging.php">Messages</a>
        <a href="profile.php" class="active">Profile</a>
        <a href="/assets/server/logout-process.php">Logout</a>
      </div>
    </div>
  </nav>
  
  <main class="dashboard">
    <!-- Profile Sidebar -->
    <aside class="profile-sidebar">
      <h2>Profile</h2>
      <p><strong>Account number:</strong><br> <?= htmlspecialchars($_SESSION['user_id']) ?></p>
      <p><strong>Account name:</strong><br> <?= htmlspecialchars($user['full_name']) ?></p>

      <label for="profile_pic">📷 Upload Profile Photo:</label>
      <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
      <button type="submit">Save</button>

      <div class="profile-image-preview">
        <img id="preview-img" src="#" alt="Profile Preview" style="display: none;">
      </div>
  </aside>

  <!-- Feed Section -->
  <section class="feed-content">
    <div class="newsfeed">
      <div class="new-post-button">
        <button onclick="openModal()">➕ New Post</button>
      </div>
      
      <h2>Your Posts</h2>

      <?php if (isset($error_message)): ?>
        <p style="color:red;"><?= $error_message ?></p>
      <?php endif; ?>

    <?php foreach ($personalPosts as $post): ?>
        <div class="post">
          <p><strong>NAME:</strong> <?= htmlspecialchars($user['full_name']) . " - " . htmlspecialchars($post['post_type']) ?></p>
          <p><strong>TITLE:</strong> <?= htmlspecialchars($post['title'])?></p>
          <p><?= htmlspecialchars($post['content'])?></p>
          <small title="Originally posted: <?= date("F j, Y - h:i A", strtotime($post['create_date']))?>">
            <?= (empty($post['edited_at']))
            ? date("F j, Y - h:i A", strtotime($post['create_date']))
            : "Edited at: " . date("F j, Y - h:i A", strtotime($post['edited_at']))?>
          </small>
        </div>
      </div>
      <?php endforeach; ?>

    <footer class="footer-tag">
      <p>Logged in as <?= htmlspecialchars($user['full_name']) ?> (<?= htmlspecialchars($_SESSION['user_id']) ?>)</p>
    </footer>
  </section>
</main>

<!---FOR JS REMOVE NALANG IF UNUSED--->

  <script>
    // Show/hide fields based on account type
    const modal = document.getElementById("newPostModal");
    const titleField = document.getElementById("post_title");
    const contentField = document.getElementById("post_content");

    function openModal() {
      modal.style.display = "flex";
      document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
      modal.style.display = "none";
      document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
      if (event.target === modal) {
        closeModal();
      }
    }

    function autoExpand(field) {
      field.style.height = 'auto';
      field.style.height = field.scrollHeight + 'px';
    }

    window.addEventListener('DOMContentLoaded', () => {
      autoExpand(titleField);
      autoExpand(contentField);
    });
  </script>
</body>
</html>