<?php
include("db.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username'])){
    $musicId = $_POST['musicId'];
    $playlistId = $_POST['playlistId'];

    // echo $musicId . "\n" . $playlistId;exit;

    $stmt = $conn->prepare("INSERT INTO music_playlist (music_id, playlist_id) VALUES (?, ?)");
    $stmt->bind_param('ii', $musicId, $playlistId);

    if($stmt->execute()){
        $_SESSION['success'] = "Music Added to Playlist Successfully!";
        header('Location: ../index.php');
        exit;
    }else{
        $_SESSION['error'] = "Something Went Wrong!";
        header('Location: ../index.php');
    }
}
?>