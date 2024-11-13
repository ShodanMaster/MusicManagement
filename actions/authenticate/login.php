<?php

include("../db.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user && password_verify($password, $user['password'])){
        // echo "Authenticated";exit;
        $_SESSION['username'] = $user['username'];
        $_SESSION['success'] = "Logged in successfully!";
        header("Location: ../../index.php");
        exit;
    }else{
        $_SESSION['error'] = "Wrong Credentials!";
        header("Location: ../../login.php");
        exit;
    }
}else {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: ../../login.php");
    exit;
}
?>