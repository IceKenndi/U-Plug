<?php

if ($_SERVER['REQUEST_METHOD'] === "POST"){
    
    require __DIR__ . "/../config/dbconfig.php";

    session_start();

    $title = $_POST['title'];
    $content = $_POST['content'];
    $authorID = $_SESSION['user_id'];
    $post_type = $_POST['post_type'];

    if(strpos($authorID, 'FAC-') === 0){
        $role = 'faculty';
        $stmt = $conn->prepare("SELECT department FROM faculty_users WHERE faculty_id = ?");
    } else if (strpos($authorID, 'STU-') === 0){
        $role = 'student';
        $stmt = $conn->prepare("SELECT department FROM student_users WHERE student_id = ?");
    } else {
        die("Invalid user.");
    }

    $stmt->bind_param("s", $authorID);
    $stmt->execute();
    $stmt->bind_result($department);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id, post_type, author_department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $content, $authorID, $post_type, $department);
    $stmt->execute();

    header("Location: /../news.php");
    exit();

}

?>
