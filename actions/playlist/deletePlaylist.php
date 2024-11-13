<?php
include("../db.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username'])){
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM playlists WHERE id = ?");
    $stmt->bind_param('i', $id);

    if($stmt->execute()){
        $_SESSION['success'] = "Playlist Deleted Successfully!";
        header('Location: ../../playlists.php');
        exit;
    }else{
        $_SESSION['error'] = "Something Went Wrong!";
        header('Location: ../../playlists.php');
        exit;
    }
}
?>