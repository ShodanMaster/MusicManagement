<?php
include("../db.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username'])){
    $id = $_POST['id'];
    $name = $_POST['name'];

    $stmt = $conn->prepare("UPDATE playlists SET name = ? WHERE id = ?");
    $stmt->bind_param('si', $name, $id);

    if($stmt->execute()){
        $_SESSION['success'] = "Playlist Updated Successfully!";
        header('Location: ../../playlists.php');
        exit;        
    }
    $_SESSION['error'] = "Something Went Wrong!";
    header('Location: ../../playlists.php');
}
?>