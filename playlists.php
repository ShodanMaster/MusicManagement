<?php
include "inc/header.php";
include("actions/db.php");

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>
<div class="card">
    <div class="card-header bg-primary text-white text-center fs-4">
        Add Playlist
    </div>
    <div class="card-body">
        <form action="actions/playlist/savePlaylist.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="name">
            </div>
            <div class="d-flex justify-content-end p-1">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="card m-3">
    <div class="card-header bg-primary text-white text-center">
        Playlist Table
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th>S.No.</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            <?php
            $res = $conn->query("SELECT * FROM playlists");
            if ($res === false) {
                echo "<tr><td colspan='6' class='text-center'>Query failed: " . $conn->error . "</td></tr>";
            } else {
                if ($res->num_rows > 0) {
                    $sno = 0;
                    while ($row = $res->fetch_array()) {
                        $sno++;
                        echo "<tr>";
                        echo "<td>" . $sno . "</td>";
                        echo "<td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>
                                <button class='btn btn-success m-1' data-bs-toggle='modal' data-bs-target='#playlistModal' 
                                        data-id='" . $row['id'] . "' 
                                        data-name='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "'>
                                    Show Playlist
                                </button>";
                        echo "<button class='btn btn-primary m-1' data-bs-toggle='modal' data-bs-target='#editModal' 
                                        data-id='" . $row['id'] . "' 
                                        data-name='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "' >
                                    Edit
                                </button>";
                        echo "<form action='actions/playlist/deletePlaylist.php' method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                    <button class='btn btn-danger m-1'>Delete</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No Data Found</td></tr>";
                }
            }
            ?>
        </table>
    </div>
</div>

<!-- Playlist Modal -->
<div class="modal fade" id="playlistModal" tabindex="-1" aria-labelledby="playlistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="playlistModalLabel">Playlist Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="playlist-content">
                    <!-- Playlist items will be dynamically loaded here -->
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Playlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="actions/playlist/updatePlaylist.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
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
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
        });

        var playlistModal = document.getElementById('playlistModal');
        playlistModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');

            document.getElementById('playlistModalLabel').innerText = "Musics Under '" + name + "' Playlist";

            fetch('actions/fetchPlaylist.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    var playlistContent = document.getElementById('playlist-content');
                    playlistContent.innerHTML = ''; 

                    if (data.length > 0) {
                        data.forEach(item => {
                            var listItem = document.createElement('li');
                            listItem.textContent = item.name;
                            playlistContent.appendChild(listItem);
                        });
                    } else {
                        playlistContent.innerHTML = '<li>No Musics in this Playlist</li>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching playlist:', error);
                    document.getElementById('playlist-content').innerHTML = '<li>Error loading playlist.</li>';
                });
        });
    });
</script>

<?php include "inc/footer.php" ?>
