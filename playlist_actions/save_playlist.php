<?php

// Save the playlist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $jsonPlaylist = $_POST['playlist'];

    // Delete the old playlist
    echo "Deleting old playlist...";
    unlink('../playlist.json');
    echo "Done!<br>";
    file_put_contents('../playlist.json', json_encode($jsonPlaylist, JSON_PRETTY_PRINT));
} else {
    http_response_code(405);
}