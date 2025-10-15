<?php

session_start();

if (isset($_SESSION['user_id'])){
    
  require __DIR__ . "/assets/config/dbconfig.php";

} else {
  header("Location: index.php");
}

$active_user = $_SESSION['user_id'];
$users = [];

$contactSql = "SELECT u.id, u.full_name, u.role, m.content, m.sent_at, m.sender_id
               FROM (SELECT student_id AS id, full_name, 'Student' AS role FROM student_users
                     UNION
                     SELECT faculty_id AS id, full_name, 'Faculty' AS role FROM faculty_users)
               AS u
               LEFT JOIN (SELECT sender_id, receiver_id, content, sent_at
                          FROM messages
                          WHERE sender_id = ? OR receiver_id = ?
                          ORDER BY sent_at DESC)
               AS m ON (m.sender_id = u.id AND m.receiver_id = ?) OR (m.receiver_id = u.id AND m.sender_id = ?)
               WHERE u.id != ?
               GROUP BY u.id
               ORDER BY MAX(m.sent_at) DESC";

$stmt = $conn->prepare($contactSql);
$stmt->bind_param("sssss", $active_user, $active_user, $active_user, $active_user, $active_user);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()){
  $row['preview'] = $row['sender_id'] === $active_user ? "You: " . $row['content'] : explode(" ", $row['full_name'])[0] . ": " . $row['content'];
  $users[] =  $row;
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>U-Plug Messaging</title>
  <link rel="stylesheet" href="/assets/css/messaging.css">
  <link rel="icon" href="/assets/images/client/UplugLogo.png" type="image/png">
</head>
<body>
  <nav class="navbar">
    <div class="navbar-center">
      <div class="logo">U-Plug</div>
      <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="news.php">News</a>
        <a href="map.php">Map</a>
        <a href="messaging.php" class="active">Messages</a>
        <a href="profile.php">Profile</a>
        <a href="/assets/server/logout-process.php">Logout</a>
      </div>
    </div>
  </nav>

  <main class="messaging-wrapper">
    <!-- Left: Contact List -->
    <aside class="contact-list" id="userList">
      <div class="contact-list-header">Contacts</div>
      <?php foreach ($users as $user): ?>
        <?php if ($user['id'] !== $active_user): ?>
          <?php 
          $lastMessage = null;
          $lastSenderId = null;

          $stmt = $conn->prepare("SELECT sender_id, content FROM messages WHERE (sender_id = ? AND receiver_id = ?)
                                                                         OR (sender_id = ? AND receiver_id = ?)
                                                                         ORDER BY sent_at DESC LIMIT 1");
          $stmt->bind_param("ssss", $active_user, $user['id'], $user['id'], $active_user);
          $stmt->execute();
          $stmt->bind_result($lastSenderId, $lastMessage);
          $stmt->fetch();
          $stmt->close();

          if ($lastSenderId === $active_user){
            $preview = 'You: ' . $lastMessage;
          } else if ($lastSenderId){
            $firstName = explode(" ", $user['full_name'])[0];
            $preview = $firstName . ": " . $lastMessage;
          } else {
            $preview = 'No message yet';
          }
          ?>
          <div class="contact-button" data-id="<?= $user['id'] ?>">
            <button type="button">
              <div class="avatar-circle"><?= strtoupper(substr($user['full_name'], 0, 1)) ?></div>
              <div class="contact-info">
                <div class="contact-name"><?= htmlspecialchars($user['full_name']) .  " - (" . htmlspecialchars($user['role']). ")"?></div>
                <div class="last-message"><?= htmlspecialchars($preview) ?></div>
              </div>
            </button>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    </aside>

    <!-- Right: Chat History + Input -->
    <section class="chat-panel">
      <div class="chat-header" id="chatHeader">Select a contact to start chatting</div>
      <div class="chat-history" id="chatHistory">
        <div class="empty-chat">
          <div class="empty-chat-icon">💬</div>
          <p>Select a contact to start chatting</p>
        </div>
      </div>
      <form class="chat-input" id="chatForm">
        <input type="text" id="messageInput" placeholder="Type your message here..." required disabled />
        <button type="submit" disabled>Send</button>
      </form>
    </section>
  </main>
  
  <script>
    let currentChatWith = null; // Set this dynamically when user clicks a contact
    let lastMessageCount = 0;

      document.querySelectorAll('.contact-button').forEach(button => {
        document.getElementById('messageInput').disabled = false;
        document.querySelector('#chatForm button').disabled = false;
        button.addEventListener('click', function() {
          currentChatWith = this.getAttribute('data-id');
          loadMessages(); // Load messages for selected contact
        });
      });
          
      function loadMessages(chatId) {
        if (!currentChatWith) return;

        fetch(`/assets/server/load-messages.php?chat_with=${currentChatWith}`)
          .then(res => res.text())
          .then(html => {
            const chatHistory = document.getElementById('chatHistory');
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newMessages = doc.querySelectorAll('.message').length;
          
            if (lastMessageCount === 0 || newMessages !== lastMessageCount) {
              chatHistory.innerHTML = html;
              lastMessageCount = newMessages;
            
              // Optional: scroll to bottom
              document.getElementById('chatHistory').scrollTop = document.getElementById('chatHistory').scrollHeight;
            }
          });
      }

      function refreshContactList() {
        fetch('/assets/server/load-contacts.php') // Create this PHP file to return updated contact HTML
          .then(res => res.text())
          .then(html => {
            document.getElementById('userList').innerHTML = html;
            attachContactListeners(); // Rebind click events
          });
      }

      function attachContactListeners() {
        document.querySelectorAll('.contact-button').forEach(button => {
          button.addEventListener('click', function () {
            currentChatWith = this.getAttribute('data-id');
            document.getElementById('chatHeader').textContent = this.querySelector('.contact-name').textContent;
            document.getElementById('messageInput').disabled = false;
            document.querySelector('#chatForm button').disabled = false;
            loadMessages();
          });
        });
      }

        setInterval(() => {
          if (currentChatWith) {
            loadMessages();
          }
          refreshContactList();
        }, 500); // every 1 second



      document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (!currentChatWith) return;

        const formData = new FormData();
        formData.append('receiver_id', currentChatWith);
        formData.append('content', document.querySelector('#messageInput textarea').value);

        fetch('send-message.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.text())
        .then(() => {
          loadMessages(); // Refresh chat
          document.getElementById('messageInput').value; // Clear input
        });
      });



      document.querySelectorAll('.contact-button').forEach(button => {
        button.addEventListener('click', function() {
          currentChatWith = this.getAttribute('data-id');
        
          // Update header
          document.getElementById('chatHeader').textContent = this.querySelector('.contact-name').textContent;
        
          // Enable input
          document.getElementById('messageInput').disabled = false;
          document.querySelector('#chatForm button').disabled = false;
        
          // Load messages
          loadMessages();
        });
      });


      document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault(); // ✅ Prevent page reload

        if (!currentChatWith) return;

        const content = document.getElementById('messageInput').value.trim();
        if (!content) return;

        const formData = new FormData();
        formData.append('receiver_id', currentChatWith);
        formData.append('content', content);

        fetch('/assets/server/send-message.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.text())
        .then(response => {
          console.log('Message sent:', response);
          loadMessages(); // ✅ Refresh chat
          document.getElementById('messageInput').value = ''; // ✅ Clear input
        })
        .catch(err => {
          console.error('Send failed:', err);
        });
      });
  </script>

</body>

</html>