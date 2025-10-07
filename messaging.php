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
  <title>U-Plug Messaging</title>
  <link rel="stylesheet" href="/assets/css/messaging.css">
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
  <main class="messaging-layout">
    <aside class="contacts-panel">
      <div class="contacts-list">
        <!-- Example contacts, replace with dynamic content from DB in PHP integration -->
        <button class="contact active">
          <span class="contact-avatar"></span>
          <span class="contact-name">Kenneth</span>
        </button>
        <button class="contact">
          <span class="contact-avatar"></span>
          <span class="contact-name">Jane</span>
        </button>
        <button class="contact">
          <span class="contact-avatar"></span>
          <span class="contact-name">Alex</span>
        </button>
        <button class="contact">
          <span class="contact-avatar"></span>
          <span class="contact-name">Maria</span>
        </button>
        <button class="contact">
          <span class="contact-avatar"></span>
          <span class="contact-name">Chris</span>
        </button>
        <button class="contact">
          <span class="contact-avatar"></span>
          <span class="contact-name">Sam</span>
        </button>
        <button class="contact">
          <span class="contact-avatar"></span>
          <span class="contact-name">Pat</span>
        </button>
        <button class="contact">
          <span class="contact-avatar"></span>
          <span class="contact-name">Kim</span>
        </button>
      </div>
    </aside>
    <section class="chat-panel">
      <div class="chat-header">
        <span>Kenneth</span>
      </div>
      <div class="chat-messages">
        <div class="message-row left">
          <div class="msg-bubble">
            <span class="msg-avatar"></span>
            <span class="msg-text">did you pray today?</span>
          </div>
        </div>
        <div class="message-row right">
          <div class="msg-bubble">
            <span class="msg-avatar"></span>
            <span class="msg-text">Yes i did</span>
          </div>
        </div>
      </div>
      <form class="send-message-form" action="#" method="post" autocomplete="off">
        <input type="text" name="message" placeholder="Type your message..." required>
        <button type="submit">Send</button>
      </form>
    </section>
  </main>
</body>
</html>