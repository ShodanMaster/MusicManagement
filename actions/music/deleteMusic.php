<?php
include("../db.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['username'])){
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM musics where id = ?");
    $stmt->bind_param('i', $id);

    if($stmt->execute()){
        $_SESSION['success'] = "Music Deleted Successfully!";
        header('Location: ../../index.php');
        exit;
    }else{
        $_SESSION['error'] = "Something Went Wrong!";
        header('Location: ../../index.php');
        exit;
    }
}
?>