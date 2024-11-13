<?php
include("../db.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])){
    $name = $_POST['name'];
    $singer = $_POST['singer'];
    $id = $_POST['id'];

    $stmt = $conn->prepare("Update musics SET name = ?, singer = ? WHERE id = ?");
    $stmt->bind_param('ssi', $name, $singer, $id);

    if($stmt->execute()){
        $_SESSION['success'] = "Music Updated Succesfully!";
        header("Location: ../../index.php");
        exit;
    }else{
        $_SESSION['error'] = "Something Went Wrong!";
        header("Location: ../../index.php");
        exit;
    }
    $stmt->close();
    $conn->close();
}

?>