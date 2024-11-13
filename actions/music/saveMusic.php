<?php
include('../db.php');
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])){
    
    $name = $_POST['name'];
    $singer = $_POST['singer'];

    $stmt = $conn->prepare("INSERT INTO musics (name, singer) VALUES (?, ?)");
    $stmt->bind_param('ss', $name, $singer);

    if($stmt->execute()){
        $_SESSION['success'] = "Music Added Successfully!";
        header('Location: ../../index.php');
        exit;
    }else{
        $_SESSION['error'] = "Something Went Wrong!";
        header('Location: ../../index.php');
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>