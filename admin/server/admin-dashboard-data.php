<?php
require __DIR__ . "/../../assets/config/dbconfig.php"; 
date_default_timezone_set('Asia/Manila');


// Optional: make sure MySQL uses PH timezone
$conn->query("SET time_zone = '+08:00'");

// Get the start and end of today (Philippine time)
$today_start = date("Y-m-d 00:00:00");
$today_end   = date("Y-m-d 23:59:59");
echo "Checking posts from $today_start to $today_end\n";

// Prepare query to get posts made today only
$stmt = $conn->prepare("
  SELECT post_id, author_id, post_type, title, content, create_date AS created_at, edited_at, author_department
  FROM posts
  WHERE create_date BETWEEN ? AND ?
  ORDER BY create_date DESC
");
$stmt->bind_param("ss", $today_start, $today_end);
$stmt->execute();

$res = $stmt->get_result();
$posts = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();


// USERS (faculty + students) in last 24 hours
// $queryFaculty = $conn->prepare("SELECT faculty_id AS id, full_name AS name, email, created_at AS joined_at FROM faculty_users WHERE created_at >= ? ORDER BY created_at DESC");
// $queryFaculty->bind_param("s", $cutoff);
// $queryFaculty->execute();
// $facultyRes = $queryFaculty->get_result();
// $faculty = $facultyRes->fetch_all(MYSQLI_ASSOC);
// $queryFaculty->close();
// 
// $queryStudent = $conn->prepare("SELECT student_id AS id, full_name AS name, email, created_at AS joined_at FROM student_users WHERE created_at >= ? ORDER BY created_at DESC");
// $queryStudent->bind_param("s", $cutoff);
// $queryStudent->execute();
// $studentRes = $queryStudent->get_result();
// $students = $studentRes->fetch_all(MYSQLI_ASSOC);
// $queryStudent->close();
// 
// $users = array_merge($faculty, $students);

// Return JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'posts' => $posts
//   'users' => $users
], JSON_UNESCAPED_UNICODE);
$conn->close();
