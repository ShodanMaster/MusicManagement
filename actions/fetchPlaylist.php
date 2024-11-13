<?php
require 'db.php'; // Make sure to include your database connection


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT M.* 
                        FROM music_playlist MP 
                        INNER JOIN musics M ON M.id = MP.music_id 
                        WHERE MP.playlist_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $playlist = [];
    while ($row = $result->fetch_assoc()) {
        $playlist[] = $row;
    }

    // Send the playlist as JSON
    echo json_encode($playlist);
} else {
    echo json_encode([]);
}
?>
