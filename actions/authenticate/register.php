<?php

include("../db.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES(?, ?)");
    $stmt->bind_param("ss", $username, $password);
    
    if($stmt->execute()){
        $_SESSION['success'] = "Registered Succesfully! Please Login.";
        header('Location: ../../login.php');
        exit;
    }else{
        $_SESSION['error'] = "Something went wrong!";
        header('Location: ../../login.php');
        exit;
    }
}else{
    $_SESSION['error'] = "Something went wrong!";
    header('Location: ../../login.php');
}

?>