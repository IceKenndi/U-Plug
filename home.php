<?php

session_start();

if (isset($_SESSION['user_id'])){
    
  require __DIR__ . "/assets/config/dbconfig.php";

} else {
  header("Location: index.php");
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Plug</title>
  <link rel="stylesheet" href="/assets/css/index.css">
  <link rel="stylesheet" href="/assets/css/auth.css">
</head>

<style>
/* Full overlay container */
#welcome-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 9999;
  pointer-events: none;
}

/* Background layer that transitions from green to black */
.color-fade-bg {
  position: absolute;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #233511 0%, #0a2a5c 100%);
  z-index: 1;
  animation: bgColorFade 10s ease forwards;
}

/* Logo container */
.logo-container {
  position: relative;
  z-index: 2;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.logo-container img {
  width: 500px;
  opacity: 0;
  animation: logoSequence 2.5s;
  z-index: 2;
}

/* Fade animations */
@keyframes fadeInLogo {
  from { opacity: 0; transform: scale(0.8); }
  to { opacity: 1; transform: scale(1); }
}

@keyframes bgColorFade {
  0%   { background-color: #233511; } /* green */
  50%  { background-color: #000000; } /* black */
  100% { background-color: #000000; opacity: 0; } /* fade out */
}

@keyframes logoSequence {
  0%   { opacity: 0; transform: scale(0.8); }
  20%  { opacity: 1; transform: scale(1); }
  80%  { opacity: 1; transform: scale(1); }
  100% { opacity: 0; transform: scale(1); }
}

:root {
  --dashboard-color: #f0f8ff; /* light blue, or whatever your dashboard uses */
}
</style>

<body>

<?php
$departmentCode = $_SESSION['department_code'] ?? 'default';
?>

  <div id="welcome-overlay">
  <div class="logo-container">
    <img src="/assets/images/client/department/<?= htmlspecialchars($departmentCode) ?>.png" alt="<?= htmlspecialchars($departmentCode) ?> Logo">
  </div>
  <div class="color-fade-bg"></div>
</div>

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
  <div class="container">
    <main class="main-content">
      <section class="news-feed">
        <h2>News Feed</h2>
        <div class="tabs">
          <button class="tab active">Faculty News</button>
          <button class="tab">Student News</button>
        </div>
        <div class="news-items">
          <div class="news-item">
            <img src="https://via.placeholder.com/60" alt="Meeting">
            <div>
              <h3>Faculty Meeting</h3>
              <p>All faculty are required to attend the monthly meeting in the main auditorium.</p>
              <small>2 hours ago</small>
            </div>
          </div>
          <div class="news-item">
            <img src="https://via.placeholder.com/60" alt="Funding">
            <div>
              <h3>Research Funding</h3>
              <p>A new research funding application is now open. Apply by the end of the month.</p>
              <small>Yesterday</small>
            </div>
          </div>
          <div class="news-item">
            <img src="https://via.placeholder.com/60" alt="Renovations">
            <div>
              <h3>Campus Renovations</h3>
              <p>The north wing will undergo renovations this summer. Classes will temporarily relocate.</p>
              <small>2 days ago</small>
            </div>
          </div>
        </div>
      </section>

      <aside class="right-panel">
        <div class="map-section">
          <h2>Campus Map</h2>
          <input type="text" placeholder="Find a building...">
          <div class="map-placeholder">[Map Here]</div>
        </div>

        <div class="messaging-section">
          <h2>Messaging</h2>
          <div class="chat">
            <p><strong>Mark Bandong</strong> <span>Student</span></p>
            <div class="message from-user">Are you free for a gloup study session fomomow?</div>
            <div class="message from-other">Yes. I am! What time and where?</div>
            <div class="message from-user">How about the library at 10 AM?</div>
          </div>
        </div>

        <div class="profile-section">
          <h2>Profile</h2>
          <div class="profile-card">
            <img src="https://via.placeholder.com/80" alt="Profile">
            <h3>Kent Barbiran</h3>
            <nav class="profile-tabs">
              <a href="#">Overview</a>
              <a href="#">Courses</a>
              <a href="#">Settings</a>
            </nav>
            <p><strong>Bio:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <p><strong>Interests:</strong> Hiking, painting, reading</p>
            <p><strong>Contact Info:</strong> -</p>
        </div>
      </aside>
    </main>
  </div>

  <script>
  setTimeout(() => {
    const overlay = document.getElementById('welcome-overlay');
    if (overlay) overlay.remove();
  }, 3500); // after full animation
</script>

</body>
</html>
</html>


