<?php

require __DIR__ . "/../config/dbconfig.php";

if (empty($_POST['role'])){
    die("Role is required (e.g. Student, Faculty)");
}

if (empty($_POST['department'])){
    die("Department is required");
}

if (empty($_POST['first_name'])){
    die("First name is required");
}

if (empty($_POST['last_name'])){
    die("Last name is required");
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    die("Valid email is required");
}

if (strlen($_POST['password']) < 8){
    die("Password must be atleast 8 characters long");
}

if (!preg_match("/[a-z]/i", $_POST['password'])){
    die("Password must atleast contain one letter");
}

if (!preg_match("/[0-9]/", $_POST['password'])){
    die("Password must atleast contain one number");
}

if ($_POST['password'] !== $_POST['password_confirmation']){
    die("Passwords must match");
}

if ($_POST['role'] === 'student') {
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO student_users (first_name, last_name, email, password_hash, department) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->stmt_init();

    if(!$stmt->prepare($sql)) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssss", $_POST['first_name'], $_POST['last_name'], $_POST['email'], $password_hash, $_POST['department']);

    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e){
        if ($e->getCode === 1062) {
            die("email is already taken");
        } else {
            die("SQL Error: " . $e->getMessage() . " " . $e->getCode());
        }
    }

    $seq_id = $conn->insert_id;

    $student_id = 'STU-' . $seq_id . "-" . $_POST['department'];
    $full_name = $_POST['first_name'] . " " . $_POST['last_name'];

    $update_sql = "UPDATE student_users SET student_id = ?, full_name = ? WHERE seq_id = ?";

    if (!$update_stmt = $conn->prepare($update_sql)){
        die("SQL error: " . $conn->error);
    }

    $update_stmt->bind_param("ssi", $student_id, $full_name, $seq_id);
    $update_stmt->execute();
}

else if ($_POST['role'] === 'faculty') {
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO faculty_users (first_name, last_name, email, password_hash, department) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->stmt_init();

    if(!$stmt->prepare($sql)) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssss", $_POST['first_name'], $_POST['last_name'], $_POST['email'], $password_hash, $_POST['department']);

    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e){
        if ($e->getCode === 1062) {
            die("email is already taken");
        } else {
            die("SQL Error: " . $e->getMessage() . " " . $e->getCode());
        }
    }

    $seq_id = $conn->insert_id;

    $faculty_id = 'FAC-' . $seq_id . "-" . $_POST['department'];
    $full_name = $_POST['first_name'] . " " . $_POST['last_name'];

    $update_sql = "UPDATE faculty_users SET faculty_id = ?, full_name = ? WHERE seq_id = ?";
    
    if (!$update_stmt = $conn->prepare($update_sql)){
        die("SQL error: " . $conn->error);
    }

    $update_stmt->bind_param("ssi", $faculty_id, $full_name, $seq_id);
    $update_stmt->execute();
}

else {
    die();
}

header("Location: /../index.php");



?>