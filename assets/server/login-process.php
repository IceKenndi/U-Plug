<?php 

if ($_SERVER['REQUEST_METHOD'] === "POST"){
    
    require __DIR__ . "/../config/dbconfig.php";

    if ($_POST['role'] === 'student'){
        $sql = sprintf("SELECT * FROM student_users WHERE email = '%s'", $conn->real_escape_string($_POST['email']));

        $result = $conn->query($sql);

        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($_POST['password'], $user['password_hash'])){

                session_start();

                session_regenerate_id();

                $_SESSION['user_id'] = $user['student_id'];

                header("Location: /../home.php");
                exit();
            } else {
            die("Incorrect Password");
        }
        } else {
            die("Email not found");
        }
    }

    else if ($_POST['role'] === 'faculty'){
        $sql = sprintf("SELECT * FROM faculty_users WHERE email = '%s'", $conn->real_escape_string($_POST['email']));

        $result = $conn->query($sql);

        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($_POST['password'], $user['password_hash'])){

                session_start();

                session_regenerate_id();

                $_SESSION['user_id'] = $user['faculty_id'];

                header("Location: /../home.php");
                exit();
            } else {
            die("Incorrect Password");
        }
        } else {
            die("Email not found");
        }
    }
}
?>