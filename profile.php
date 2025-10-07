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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile</title>
  <link rel="stylesheet" href="/assets/css/profile.css">
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
  <main class="profile-main">
    <div class="profile-card">
      <h2>Profile</h2>
      <form id="profile-form" enctype="multipart/form-data" autocomplete="off">
        <label for="account_name">Account number:</label>
        <p> <?= htmlspecialchars($_SESSION['user_id'])?></p> 
        
        <label for="account_name">Account name:</label>
        <p> <?= htmlspecialchars($user['full_name'])?></p> 

        <label for="profile_pic">Profile Picture:</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required>

        <button type="submit">Save Profile</button>
      </form>
      <div id="profile-preview"></div>
    </div>
  </main>


<!---FOR JS REMOVE NALANG IF UNUSED--->

  <script>
    // Show/hide fields based on account type
    const accountType = document.getElementById('account_type');
    const studentFields = document.getElementById('student-fields');
    const facultyFields = document.getElementById('faculty-fields');
    const studentInputs = studentFields.querySelectorAll('input');
    const facultyInputs = facultyFields.querySelectorAll('input');

    accountType.addEventListener('change', function() {
      if (this.value === 'student') {
        studentFields.style.display = 'block';
        facultyFields.style.display = 'none';
        studentInputs.forEach(input => input.required = true);
        facultyInputs.forEach(input => input.required = false);
      } else if (this.value === 'faculty') {
        studentFields.style.display = 'none';
        facultyFields.style.display = 'block';
        studentInputs.forEach(input => input.required = false);
        facultyInputs.forEach(input => input.required = true);
      } else {
        studentFields.style.display = 'none';
        facultyFields.style.display = 'none';
        studentInputs.forEach(input => input.required = false);
        facultyInputs.forEach(input => input.required = false);
      }
    });

    // Preview uploaded profile picture
    document.getElementById('profile_pic').addEventListener('change', function(event) {
      const preview = document.getElementById('profile-preview');
      preview.innerHTML = '';
      const file = event.target.files[0];
      if (file) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.maxWidth = '120px';
        img.style.maxHeight = '120px';
        img.style.marginTop = '1rem';
        img.alt = "Profile Preview";
        preview.appendChild(img);
      }
    });

    // Prevent actual form submission for demo
    document.getElementById('profile-form').onsubmit = function(e) {
      e.preventDefault();
      alert('Profile saved (demo only)');
    };
  </script>
</body>
</html>