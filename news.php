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

    $sql = "SELECT * FROM posts ORDER BY COALESCE(edited_at, create_date) DESC";
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

    $sql = "SELECT * FROM posts ORDER BY COALESCE(edited_at, create_date) DESC";
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
      $post['authorRole'] = strpos($authorId, 'STU-') === 0 ? 'Student' : 'Faculty';
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

//tab

$activeTab = $_GET['tab'] ?? 'official';

//tab
if (isset($_GET['edit'])) {
  $editId = $_GET['edit'];
  foreach (array_merge($studentPosts, $facultyPosts) as $post) {
    if ($post['post_id'] == $editId && $post['author_id'] === $_SESSION['user_id']) {
      $activeTab = 'department';
      break;
    }
  }
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
        <a href="news.php?tab=official" class="tab <?= $activeTab === 'official' ? 'active' : '' ?>">Official News</a>
        <a href="news.php?tab=department" class="tab <?= $activeTab === 'department' ? 'active' : '' ?>"><?= htmlspecialchars($user['department']) ?> News</a>
      </div>
      <div id="official" class="news-section <?= $activeTab === 'official' ? '' : 'hidden' ?>">
        <?php foreach ($officialPosts as $post): ?>
          <div class="news-card" data-post-id="<?= $post['post_id'] ?>">
            <?php if (isset($_GET['edit']) && $_GET['edit'] == $post['post_id'] && $post['author_id'] === $_SESSION['user_id']): ?>
        <!-- Editable form -->
        <form action="/assets/server/edit-post.php" method="POST">
          <input type="hidden" name="tab" value="official">
          <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
          <label>Title:</label><br>
          <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>"><br><br>
          <label>Content:</label><br>
          <textarea name="content"><?= htmlspecialchars($post['content']) ?></textarea><br><br>
          <input type="submit" value="Save">
          <a href="news.php">Cancel</a>
        </form>

        <!-- Delete form -->
              <?php if (isset($_GET['delete']) && $_GET['delete'] == $post['post_id'] && $post['author_id'] === $_SESSION['user_id']): ?>
                <div class="confirm-card">
                  <p>Are you sure you want to delete this post?</p>
                  <form action="/assets/server/delete-post.php" method="POST" style="display:inline;">
                    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                    <input type="hidden" name="tab" value="<?= $activeTab ?>">
                    <input type="submit" value="Confirm" class="confirm-btn">
                  </form>
                  <a href="news.php?tab=<?= $activeTab ?>" class="cancel-btn">Cancel</a>
                </div>
              <?php endif; ?>
        <!-- Delete form -->

          <?php else: ?>
        <!-- Regular display -->
            <strong><?= htmlspecialchars($post['title']) ?></strong><br>
              <p><?= htmlspecialchars($post['content']) ?></p>
              <em><?= htmlspecialchars($post['authorName']) ?> (<?= htmlspecialchars($post['authorRole']) ?> - <?= htmlspecialchars($post['author_department']) ?>)</em><br>
              <em>Posted: <?= date("F j, Y - h:i A", strtotime($post['create_date'])) ?></em><br>
              <?php if (!empty($post['edited_at'])): ?>
              <em>Edited: <?= date("F j, Y - h:i A", strtotime($post['edited_at'])) ?></em><br>
              <?php endif; ?>

              <?php if ($post['author_id'] === $_SESSION['user_id']): ?>
                <form method="GET" action="news.php" style="margin-top: 0.5rem;">
                  <input type="hidden" name="edit" value="<?= $post['post_id'] ?>">
                  <input type="submit" value="Edit">
                </form>
              <?php endif; ?>
              <!-- Delete form -->
              <?php if (
                isset($_GET['delete']) &&
                $_GET['delete'] == $post['post_id'] &&
                $post['author_id'] === $_SESSION['user_id']
              ): ?>
                <!-- Confirmation Card -->
                <div class="confirm-card">
                  <p>Are you sure you want to delete this post?</p>
                  <form action="/assets/server/delete-post.php" method="POST" style="display:inline;">
                    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                    <input type="hidden" name="tab" value="<?= $activeTab ?>">
                    <input type="submit" value="Confirm" class="confirm-btn">
                  </form>
                  <a href="news.php?tab=<?= $activeTab ?>" class="cancel-btn">Cancel</a>
                </div>
              <?php else: ?>
                <!-- Delete Trigger Button -->
                <?php if ($post['author_id'] === $_SESSION['user_id']): ?>
                  <form method="GET" action="news.php" style="margin-top: 0.5rem;">
                    <input type="hidden" name="delete" value="<?= $post['post_id'] ?>">
                    <input type="hidden" name="tab" value="<?= $activeTab ?>">
                    <input type="submit" value="Delete" class="delete-btn">
                  </form>
                <?php endif; ?>
              <?php endif; ?>
              <!-- Delete form -->
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <div id="department" class="news-section <?= $activeTab === 'department' ? '' : 'hidden' ?>">
        <?php foreach (array_merge($studentPosts, $facultyPosts) as $post): ?>
          <div class="news-card" data-post-id="<?= $post['post_id'] ?>">
            <?php if (isset($_GET['edit']) && $_GET['edit'] == $post['post_id'] && $post['author_id'] === $_SESSION['user_id']): ?>
        <!-- Editable form -->
        <form action="/assets/server/edit-post.php" method="POST">
          <input type="hidden" name="tab" value="department">
          <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
          <label>Title:</label><br>
          <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>"><br><br>
          <label>Content:</label><br>
          <textarea name="content"><?= htmlspecialchars($post['content']) ?></textarea><br><br>
          <input type="submit" value="Save">
          <a href="news.php">Cancel</a>
        </form>
          <?php else: ?>

        <!-- Regular display -->
            <strong><?= htmlspecialchars($post['title']) ?></strong><br>
              <p><?= htmlspecialchars($post['content']) ?></p>
              <em><?= htmlspecialchars($post['authorName']) ?> (<?= htmlspecialchars($post['authorRole']) ?>)</em><br>
              <em>Posted: <?= date("F j, Y - h:i A", strtotime($post['create_date'])) ?></em><br>
              <?php if (!empty($post['edited_at'])): ?>
              <em>Edited: <?= date("F j, Y - h:i A", strtotime($post['edited_at'])) ?></em><br>
              <?php endif; ?>

              <?php if ($post['author_id'] === $_SESSION['user_id']): ?>
                <form method="GET" action="news.php" style="margin-top: 0.5rem;">
                  <input type="hidden" name="edit" value="<?= $post['post_id'] ?>">
                  <input type="submit" value="Edit">
                </form>
              <?php endif; ?>
              <!-- Delete form -->
              <?php if (
                isset($_GET['delete']) &&
                $_GET['delete'] == $post['post_id'] &&
                $post['author_id'] === $_SESSION['user_id']
              ): ?>
                <!-- Confirmation Card -->
                <div class="confirm-card">
                  <p>Are you sure you want to delete this post?</p>
                  <form action="/assets/server/delete-post.php" method="POST" style="display:inline;">
                    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                    <input type="hidden" name="tab" value="<?= $activeTab ?>">
                    <input type="submit" value="Confirm" class="confirm-btn">
                  </form>
                  <a href="news.php?tab=<?= $activeTab ?>" class="cancel-btn">Cancel</a>
                </div>
              <?php else: ?>
                <!-- Delete Trigger Button -->
                <?php if ($post['author_id'] === $_SESSION['user_id']): ?>
                  <form method="GET" action="news.php" style="margin-top: 0.5rem;">
                    <input type="hidden" name="delete" value="<?= $post['post_id'] ?>">
                    <input type="hidden" name="tab" value="<?= $activeTab ?>">
                    <input type="submit" value="Delete" class="delete-btn">
                  </form>
                <?php endif; ?>
              <?php endif; ?>
              <!-- Delete form -->
            <?php endif; ?>
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
