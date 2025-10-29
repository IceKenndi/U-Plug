<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
  header("Location: /../home.php");
  exit();
}

require __DIR__ . "/../assets/config/dbconfig.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>U-Plug Admin Dashboard</title>
  <link rel="stylesheet" href="/assets/css/admin-dashboard.css">
  <link rel="icon" href="/assets/images/client/UplugLogo.png" type="image/png">
</head>
<body>

  <div class="layout" id="layout">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="logo">Administrator</div>
        <ul class="nav">
          <li><a href="admin.php" class="nav-link">Dashboard</a></li>
          <li class="divider">User Settings</li>
          <li><a href="faculty.php" class="nav-link">Faculty Users</a></li>
          <li><a href="student.php" class="nav-link">Student Users</a></li>
          <li><a href="posts.php" class="nav-link">Posts</a></li>
          <li class="divider">Settings</li>
          <li><a href="#" class="nav-link">About</a></li>
          <li><a href="/assets/server/logout-process.php" class="nav-link">Logout</a></li>
        </ul>
    </aside>
    <!-- Burger Button -->
    <button id="burger" class="burger">&#9776;</button>

    <!-- Main Content -->
    <main class="main-content" id="main">
      <section class="summary-cards">
        <div class="card">
          <h3>Posts Today</h3>
          <p id="post-count">0</p>
        </div>
        <div class="card">
          <h3>New Users</h3>
          <p id="user-count">0</p>
        </div>
      </section>

      <!-- Recent Posts -->
      <section class="panel" id="recent-posts-panel">
        <div class="panel-header">
          <h2>Recent Posts</h2>
          <button class="expand-btn" data-target="recent-posts">＋</button>
        </div>
        <ul class="list collapsible" id="recent-posts"></ul>
      </section>

      <!-- New Users -->
      <section class="panel" id="new-users-panel">
        <div class="panel-header">
          <h2>New Users</h2>
          <button class="expand-btn" data-target="new-users">＋</button>
        </div>
        <ul class="list collapsible" id="new-users"></ul>
      </section>

      <div class="footer">
        U-Plug ©2025. All rights reserved.
      </div>
    </main>
  </div>

  <!-- Edit Modal -->
  <div id="edit-modal" class="modal">
    <div class="modal-content large">
      <span class="close-btn">&times;</span>
      <h3>Edit Post</h3>
      <label for="edit-author">Author:</label>
      <input type="text" id="edit-author" placeholder="Author name..." />
      <label for="edit-content">Content:</label>
      <textarea id="edit-content" placeholder="Post content..." rows="4"></textarea>
      <label for="edit-date">Date:</label>
      <input type="text" id="edit-date" placeholder="YYYY-MM-DD HH:MM" />
      <button id="save-edit">Save</button>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="delete-modal" class="modal">
    <div class="modal-content small">
      <h3>Confirm Deletion</h3>
      <p>Are you sure you want to delete this entry?</p>
      <div class="modal-actions">
        <button id="confirm-delete">Yes</button>
        <button id="cancel-delete">No</button>
      </div>
    </div>
  </div>

  <!-- View User Modal -->
  <div id="view-user-modal" class="modal">
    <div class="modal-content large">
      <span class="close-btn">&times;</span>
      <h3>User Details</h3>
      <p><strong>Profile Name:</strong> <span id="view-user-name">—</span></p>
      <p><strong>Email:</strong> <span id="view-user-email">—</span></p>
      <p><strong>Joined:</strong> <span id="view-user-date">—</span></p>
      <h4>Recent Posts</h4>
      <ul id="user-recent-posts">
        <li>Placeholder Post 1: This is a sample post content...</li>
        <li>Placeholder Post 2: Another example of user-generated content...</li>
        <li>Placeholder Post 3: More placeholder text for demonstration...</li>
      </ul>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const burger = document.getElementById('burger');
      const sidebar = document.getElementById('sidebar');
    
      // Highlight active nav
      const currentPage = window.location.pathname.split("/").pop() || "admin.html";
      document.querySelectorAll(".nav-link").forEach(link => {
        link.classList.toggle("active", link.getAttribute("href") === currentPage);
      });
    
      burger.addEventListener('click', () => sidebar.classList.toggle('hidden'));
    
      // Expand buttons
      document.querySelectorAll('.expand-btn').forEach(button => {
        button.addEventListener('click', () => {
          const targetId = button.getAttribute('data-target');
          const panel = document.getElementById(`${targetId}-panel`);
          panel.classList.toggle('expanded');
          button.classList.toggle('expanded');
          button.textContent = button.classList.contains('expanded') ? '−' : '＋';
        });
      });
    
      // === Fetch Live Data from Server (replace sample data) ===
      fetch("./admin-dashboard-data.php")
        .then(res => {
          if (!res.ok) throw new Error("Network response was not ok");
          return res.json();
        })
        .then(data => {
          const recentPosts = data.posts || [];
          const newUsers = data.users || [];
        
          // Update counters
          document.getElementById("post-count").textContent = recentPosts.length;
          document.getElementById("user-count").textContent = newUsers.length;
        
          // Populate posts
          const postList = document.getElementById("recent-posts");
          postList.innerHTML = "";
          recentPosts.forEach(post => {
            const li = document.createElement("li");
            // adjust fields to match what your PHP returned (department, author_id, created_at, etc.)
            const department = post.department || "";
            const author = post.author_id || "";
            const title = post.title || "";
            const contentPreview = (post.content || "").substring(0, 80);
            const createdAt = post.created_at || post.create_date || "";
            li.innerHTML = `
              <div>|${department}| ${author} | ${title} | ${contentPreview}... | ${createdAt}</div>
              <div class="actions">
                <button class="edit-btn">View</button>
                <button class="delete-btn">Delete</button>
              </div>`;
            postList.appendChild(li);
          });
        
          // Populate users
          const userList = document.getElementById("new-users");
          userList.innerHTML = "";
          newUsers.forEach(user => {
            const li = document.createElement("li");
            const name = user.name || "";
            const email = user.email || "";
            const joined = user.joined_at || "";
            li.innerHTML = `
              <div>${name} | ${email} | ${joined}</div>
              <div class="actions">
                <button class="edit-btn">View</button>
                <button class="delete-btn">Delete</button>
              </div>`;
            userList.appendChild(li);
          });
        })
        .catch(err => {
          console.error("Error loading dashboard data:", err);
          // Optionally show an error to admin UI
        });
      
      // === Modal Logic (keep as-is) ===
      // ... (the rest of your modal logic)
    });
    </script>

</body>
</html>
