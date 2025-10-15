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
  <title>U-Plug Campus Map</title>
  <link rel="stylesheet" href="/assets/css/map.css">
</head>
<body>
  <nav class="navbar">
    <div class="navbar-center">
      <div class="logo">U-Plug</div>
      <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="news.php">News</a>
        <a href="map.php" class="active">Map</a>
        <a href="messaging.php">Messages</a>
        <a href="profile.php">Profile</a>
        <a href="/assets/server/logout-process.php">Logout</a>
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
  </script>
</body>
</html>