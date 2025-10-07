<?php

session_start();

if (isset($_SESSION['user_id'])){
    
  require __DIR__ . "/assets/config/dbconfig.php";

  $session_id = $_SESSION['user_id'];

  if (strpos($session_id, 'FAC-') === 0){

    $sql = "SELECT * FROM faculty_users WHERE faculty_id = '$session_id'";

    $result = $conn->query($sql);

    $user = $result->fetch_assoc();
//post// //learn today//
    
    $posts = [];
    $studentPosts = [];
    $facultyPosts = [];
    $officialPosts = [];

    $sql = "SELECT * FROM posts ORDER BY create_date DESC";
    $result = $conn->query($sql);

    while ($post = $result->fetch_assoc()) {
      $authorId = $post['author_id'];

      if (strpos($authorId, 'STU-') === 0){
        $stmt = $conn->prepare("SELECT full_name, department FROM student_users WHERE student_id = ?");
      } else if (strpos($authorId, 'FAC-') === 0){
        $stmt = $conn->prepare("SELECT full_name, department FROM faculty_users WHERE faculty_id = ?");
      } else {
        $post['authorName'] = 'Unknown';
        $post['authorRole'] = 'Unknown';
        $post['authorDept'] = 'Unknown';
      }

      $stmt->bind_param("s", $authorId);
      $stmt->execute();
      $stmt->bind_result($authorName, $authorDept);
      $stmt->fetch();
      $stmt->close();
      
      $post['authorName'] = $authorName;
      $post['authorRole'] = strpos($authorId, 'STU-') === 0 ? 'student' : 'faculty';
      $post['authorDept'] = $authorDept;

      if ($post['post_type'] === 'official'){
        $officialPosts[] = $post;
      } else if ($post['post_type'] === 'department' && $post['authorDept'] === $user['department']) {
        if ($post['authorRole'] === 'student'){
          $studentPosts[] = $post;
        } else {
          $facultyPosts[] = $post;
        }
      }
    }

//post// //learn today//
  } else if (strpos($session_id, 'STU-') === 0){

    $sql = "SELECT * FROM student_users WHERE student_id = '$session_id'";

    $result = $conn->query($sql);

    $user = $result->fetch_assoc();
//post// //learn today//
    
    $posts = [];
    $studentPosts = [];
    $facultyPosts = [];
    $officialPosts = [];

    $sql = "SELECT * FROM posts ORDER BY create_date DESC";
    $result = $conn->query($sql);

    while ($post = $result->fetch_assoc()) {
      $authorId = $post['author_id'];

      if (strpos($authorId, 'STU-') === 0){
        $stmt = $conn->prepare("SELECT full_name, department FROM student_users WHERE student_id = ?");
      } else if (strpos($authorId, 'FAC-') === 0){
        $stmt = $conn->prepare("SELECT full_name, department FROM faculty_users WHERE faculty_id = ?");
      } else {
        $post['authorName'] = 'Unknown';
        $post['authorRole'] = 'Unknown';
        $post['authorDept'] = 'Unknown';
      }

      $stmt->bind_param("s", $authorId);
      $stmt->execute();
      $stmt->bind_result($authorName, $authorDept);
      $stmt->fetch();
      $stmt->close();
      
      $post['authorName'] = $authorName;
      $post['authorRole'] = strpos($authorId, 'STU-') === 0 ? 'student' : 'faculty';
      $post['authorDept'] = $authorDept;

      if ($post['post_type'] === 'official'){
        $officialPosts[] = $post;
      } else if ($post['post_type'] === 'department' && $post['authorDept'] === $user['department']) {
        if ($post['authorRole'] === 'student'){
          $studentPosts[] = $post;
        } else {
          $facultyPosts[] = $post;
        }
      }
    }

//post// //learn today//
  }

} else {
  header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>U-Plug News</title>
  <link rel="stylesheet" href="/assets/css/news.css">
</head>
<body>
<nav class="navbar">
  <div class="navbar-center">
    <div class="logo">U-Plug</div>
    <div class="nav-links">
      <a href="home.php">Home</a>
      <a href="news.php">News</a>
      <a href="map.php">Map</a>
      <a href="messaging.php">Messages</a>
      <a href="profile.php">Profile</a>
      <a href="/assets/server/logout-process.php">Logout</a>
    </div>
  </div>
</nav>
  <main>
    <section class="news-feed">
      <form action="/assets/server/create-post.php" method="POST">
        <h2>Create official post</h2>

        <input type="hidden" name="post_type" value="official">
        <label for="title">Input title: </label>
        <input type="text" name="title">
        <br>
        <label for="content">Content: </label>
        <input type="text" name="content">
        <br>
        <input type="submit" value="post">

      </form>

      <form action="/assets/server/create-post.php" method="POST">
        <h2>Create department post</h2>

        <input type="hidden" name="post_type" value="department">
        <label for="title">Input title: </label>
        <input type="text" name="title">
        <br>
        <label for="content">Content: </label>
        <input type="text" name="content">
        <br>
        <input type="submit" value="post">

      </form>
    </section>

    <section class="news-feed">
      <h2>News</h2>
      <div class="tabs">
        <button class="tab active" onclick="showSection('official')">Official News</button>
        <button class="tab" onclick="showSection('department')"><?= htmlspecialchars($user['department'])?> News</button>
      </div>
      <div id="official" class="news-section">
        <?php foreach ($officialPosts as $post): ?>
          <div class="news-card">
            <strong><?= htmlspecialchars($post['title']) ?></strong><br>
            <?= htmlspecialchars($post['content']) ?><br>
            <em>Posted by <?= htmlspecialchars($post['authorName']) ?> (<?= htmlspecialchars($post['authorRole'])?>)</em>
          </div>
        <?php endforeach; ?>
      </div>
      <div id="department" class="news-section hidden">
        <?php foreach ($studentPosts as $post): ?>
          <div class="news-card">
            <strong><?= htmlspecialchars($post['title']) ?></strong><br>
            <?= htmlspecialchars($post['content']) ?><br>
            <em>Posted by <?= htmlspecialchars($post['authorName']) ?> (Student)</em>
          </div>
        <?php endforeach; ?>
        <?php foreach ($facultyPosts as $post): ?>
          <div class="news-card">
            <strong><?= htmlspecialchars($post['title']) ?></strong><br>
            <?= htmlspecialchars($post['content']) ?><br>
            <em>Posted by <?= htmlspecialchars($post['authorName']) ?> (Faculty)</em>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <!---FOR JS--->

  <script>
    function showSection(section) {
      document.getElementById('official').classList.add('hidden');
      document.getElementById('department').classList.add('hidden');
      document.getElementById(section).classList.remove('hidden');
      // Tab highlight
      document.querySelectorAll('.tab').forEach(btn => btn.classList.remove('active'));
      if(section === 'official') {
        document.querySelectorAll('.tab')[0].classList.add('active');
      } else {
        document.querySelectorAll('.tab')[1].classList.add('active');
      }
    }
  </script>
</body>
</html>
