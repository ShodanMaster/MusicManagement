<?php

include "inc/header.php";
include("actions/db.php");

if(!isset($_SESSION['username'])){
    header('Location: login.php');
    exit;
}
?>
<div class="card">
    <div class="card-header bg-primary text-white text-center fs-4">
        Add Music
    </div>
    <div class="card-body">
        <form action="actions/music/saveMusic.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="name">
            </div>
            <div class="form-group">
                <label for="singer" class="form-label">Singer:</label>
                <input type="text" class="form-control" id="singer" name="singer" placeholder="Singer">
            </div>
            <div class="d-flex justify-content-end p-1">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="card m-3">
    <div class="card-header bg-primary text-white text-center">
        Musics Table
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th>S.No.</th>
                <th>Name</th>
                <th>Singer</th>
                <th>Action</th>
            </tr>
            <?php 
                $res = $conn->query("SELECT * FROM musics");
                if ($res === false) {
                    // If the query fails, display the error
                    echo "<tr><td colspan='6' class='text-center'>Query failed: " . $conn->error . "</td></tr>";
                } else {
                    if ($res->num_rows > 0) {
                        $sno = 0;
                        while ($row = $res->fetch_array()) {
                            $sno++;
                            echo "<tr>"; 
                            echo "<td>" . $sno . "</td>";
                            echo "<td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>"; 
                            echo "<td>" . htmlspecialchars($row['singer'], ENT_QUOTES, 'UTF-8') . "</td>"; 
                            echo "<td>
                                    <button class='btn btn-success m-1' data-bs-toggle='modal' data-bs-target='#playlistModal' 
                                            data-id='" . $row['id'] . "' 
                                            data-name='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "'>
                                        Add to Playlist
                                    </button>";
                            echo "<button class='btn btn-primary m-1' data-bs-toggle='modal' data-bs-target='#editModal' 
                                            data-id='" . $row['id'] . "' 
                                            data-name='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "' 
                                            data-singer='" . htmlspecialchars($row['singer'], ENT_QUOTES, 'UTF-8') . "'>
                                        Edit
                                    </button>";
                            echo "<form action='actions/music/deleteMusic.php' method='POST'>
                                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                                        <button class='btn btn-danger m-1'>Delete</button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                    }
                }
            ?>
        </table>
    </div>
</div>

<!--Playlsit Modal Structure -->
<div class="modal fade" id="playlistModal" tabindex="-1" aria-labelledby="playlistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="playlistModalLabel">playlist Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="actions/makePlaylist.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="playlist-id" name="musicId">
                    <div class="form-group mb-3">
                        <label for="playlist-name" class="form-label">Name</label>
                        <select name="playlistId" id="playlist-name" class="form-control" required>
                            <option value="" disabled selected>Select</option>
                            <?php
                                $plres = $conn->query("SELECT * FROM playlists");
                                if ($plres->num_rows > 0) {
                                        $sno = 0;
                                        while ($row = $plres->fetch_array()) {
                                            echo "<option value ='". $row['id'] ."' >".$row['name']."</option>";
                                        }
                                    }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Edit Modal Structure -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="actions/music/updateMusic.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-singer" class="form-label">singer</label>
                        <input type="text" class="form-control" id="edit-singer" name="singer">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget;
            // Extract info from data-* attributes
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var singer = button.getAttribute('data-singer');

            // Update the modal's input fields
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-singer').value = singer;
        });
        
        var playlistModal = document.getElementById('playlistModal');
        playlistModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget;
            // Extract info from data-* attributes
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');

            // Update the modal's input fields
            document.getElementById('playlist-id').value = id;
            document.getElementById('playlistModalLabel').innerText = "Add '" +name+ "' to Playlist.";
            
        });
    });
</script>

<?php include "inc/footer.php"?>