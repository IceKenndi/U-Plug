<?php

session_start();

if (isset($_SESSION['user_id'])){
    
  require __DIR__ . "/assets/config/dbconfig.php";

} else {
  header("Location: index.php");
}

// PUSH NOTIF

$stmt = $conn->prepare("SELECT post_id, title, create_date, edited_at, toast_status, toast_message FROM posts WHERE toast_status = 1 AND author_id != ?");
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$result = $stmt->get_result();

$toastPosts = [];
while ($row = $result->fetch_assoc()) {
  $toastPosts[] = $row;
}

// PUSH NOTIF

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>U-Plug Campus Map</title>
  <link rel="stylesheet" href="/assets/css/map.css">
  <link rel="icon" href="/assets/images/client/UplugLogo.png" type="image/png">
  <script src="/assets/javascript/toast-notif.js" defer></script>
</head>
<body>

<div id="toastContainer" class="toast-container">
  <?php foreach ($toastPosts as $post): ?>
    <div class="toast" data-post-id="<?= $post['post_id'] ?>">
      <span><?= htmlspecialchars($post['toast_message']) ?></span><br>
      <small style="opacity: 0.8;">
        <?= empty($post['edited_at'])
          ? date("F j, Y - h:i A", strtotime($post['create_date']))
          : "Edited at: " . date("F j, Y - h:i A", strtotime($post['edited_at'])) ?>
      </small>
      <button class="dismiss-toast">X</button>
    </div>
  <?php endforeach; ?>
</div>

  <nav class="navbar">
    <div class="nav-left">
      <div class="logo">U-Plug</div>
      <div class="nav-links">
        <a href="/home.php">Home</a>
        <a href="/news.php">News</a>
        <a href="/map.php" class="active">Map</a>
        <a href="/messaging.php">Messages</a>
        <a href="/profile.php">Profile</a>
        <a href="/assets/server/logout-process.php">Logout</a>
      </div>
    </div>
    <div class="nav-right">
      <div class="search-wrapper">
        <input type="text" id="searchInput" placeholder="Search profiles by name..." autocomplete="off">
        <div id="searchResults"></div>
      </div>
    </div>
  </nav>
  <main class="map-main">
    <section class="map-controls">
      <h2>Campus Map</h2>
      <label for="building-select">Choose a building:</label>
      <select id="building-select">
        <option value="">-- Select a building --</option>
        <option value="student-plaza">Student Plaza</option>
        <option value="riverside-building">Riverside Building</option>
        <option value="mba">MBA</option>
        <option value="mba-hall">MBA Hall</option>
        <option value="north-hall">North Hall</option>
        <option value="gym">Gym</option>
        <option value="chs">CHS</option>
        <option value="basic-ed">Basic Ed</option>
        <option value="ptc">PTC</option>
        <option value="csdl-its">CSDL ITS</option>
        <option value="op">OP</option>
        <option value="fvr">FVR</option>
        <option value="cma">CMA</option>
        <option value="phinma-garden">Phinma Garden</option>
        <option value="old-stage">Old Stage</option>
        <option value="main-entrance-gate">Main Entrance Gate</option>
      </select>
    </section>
    <section class="map-display">
      <img src="campus-map.jpg" alt="Campus Map" id="campus-map" />
      <!-- You can use a transparent overlay or highlight for selected building if desired -->
      <div id="building-info" class="building-info"></div>
    </section>
  </main>


  <!-- FOR JS --> 
   
  <script>
    // Example: Show building info on selection
    const buildingInfo = {
      "student-plaza": "Student Plaza: Main student activity area.",
      "riverside-building": "Riverside Building: Classrooms and offices.",
      "mba": "MBA: Engineering building.",
      "mba-hall": "MBA Hall: Lecture and event hall.",
      "north-hall": "North Hall: Academic classrooms.",
      "gym": "Gym: Sports and PE activities.",
      "chs": "CHS: College of Health Sciences.",
      "basic-ed": "Basic Ed: Basic Education Department.",
      "ptc": "PTC: Professional Training Center.",
      "csdl-its": "CSDL ITS: Computer Science and IT Services.",
      "op": "OP: Office of the President.",
      "fvr": "FVR: Faculty and admin offices.",
      "cma": "CMA: College of Management and Accountancy.",
      "phinma-garden": "Phinma Garden: Campus green space.",
      "old-stage": "Old Stage: Outdoor event area.",
      "main-entrance-gate": "Main Entrance Gate: Campus entry point."
    };
    document.getElementById('building-select').addEventListener('change', function() {
      const val = this.value;
      document.getElementById('building-info').textContent = buildingInfo[val] || '';
    });

    fetch('assets/server/load-toasts.php')
  .then(res => res.json())
  .then(data => {
    if (Array.isArray(data)) {
      data.forEach(toast => {
        showToast(toast.message, toast.type, 'poll', toast.link);

        fetch('assets/server/ack-toast.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `message=${encodeURIComponent(toast.message)}`
        });
      });
    }
  });

  </script>

  <!-- SEARCH PROFILE -->

    <script>
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    searchInput.addEventListener('input', function () {
      const query = this.value.trim();

      if (query.length === 0) {
        searchResults.style.display = 'none';
        searchResults.innerHTML = '';
        return;
      }

      fetch('/assets/server/search-profile.php?q=' + encodeURIComponent(query))
        .then(res => res.text())
        .then(html => {
          searchResults.innerHTML = html;
          searchResults.style.display = 'block';
        });
    });
    </script>

    <script>
    function viewProfile(userId) {
      window.location.href = '/assets/server/view-profile.php?user_id=' + encodeURIComponent(userId);
    }
    </script>

    <!-- SEARCH PROFILE -->

<div id="toastContainer" class="toast-container"></div>

</body>
</html>