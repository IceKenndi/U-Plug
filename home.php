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
</body>
</html>
</html>
