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

<!-- Floating tab for posts -->
 <div id="newPostModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <h3 id="modalTitle">Create New Post</h3>
    <form method="POST" action="/assets/server/create-post.php" autocomplete="off" class="post-form">
      <label for="wallSelect">Choose Wall to Post On:</label>
      <select id="wallSelect" name="post_type" required>
        <option value="official">📢 Official News</option>
        <option value="department">🏛️ <?= htmlspecialchars($user['department']) ?> News</option>
      </select>

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
      <a href="news.php" class="active">News</a>
      <a href="map.php">Map</a>
      <a href="messaging.php">Messages</a>
      <a href="profile.php">Profile</a>
      <a href="/assets/server/logout-process.php">Logout</a>
    </div>
  </div>
</nav>

  <main>
    <section class="news-feed">
      <h2>📰 News</h2>
      <div class="tabs">
        <button class="tab" onclick="openModal()">➕ Create Post</button>
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
              <em title="Originally posted: <?= date("F j, Y - h:i A", strtotime($post['create_date']))?>">
                <?= (empty($post['edited_at']))
                ? date("F j, Y - h:i A", strtotime($post['create_date']))
                : "Edited at: " . date("F j, Y - h:i A", strtotime($post['edited_at']))?>
              </em>

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
              <em title="Originally posted: <?= date("F j, Y - h:i A", strtotime($post['create_date']))?>">
                <?= (empty($post['edited_at']))
                ? date("F j, Y - h:i A", strtotime($post['create_date']))
                : "Edited at: " . date("F j, Y - h:i A", strtotime($post['edited_at']))?>
              </em>

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
    const modal = document.getElementById("newPostModal");
    const titleField = document.getElementById("post_title");
    const contentField = document.getElementById("post_content");
    
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

    // Open Post Tab
    function openModal() {
      modal.classList.add("show");
      document.body.style.overflow = "hidden";
    }
    // Close Post Tab
    function closeModal() {
      modal.classList.remove("show");
      document.body.style.overflow = "auto";
    }

    //event listener
    document.addEventListener("keydown", function (event) {
      const modal = document.getElementById("newPostModal");
      if (event.key === "Escape" && modal.classList.contains("show")) {
        closeModal();
      }
    });
  </script>
</body>
</html>
