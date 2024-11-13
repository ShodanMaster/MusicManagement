<?php
include("../db.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username'])){
    $name = $_POST['name'];
    
    $stmt = $conn->prepare("INSERT INTO playlists (name) VALUES(?)");
    $stmt->bind_param('s', $name);

    if($stmt->execute()){
        $_SESSION['success'] = "Playlist Added Successfully!";
        header('Location: ../../playlists.php');
        exit;
    }else{
        $_SESSION['error'] = "Something Went Wrong!";
        header('Location: ../../playlists.php');
    }
}
?>