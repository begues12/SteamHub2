<?php
if (isset($_POST['videoName']))
{

    // Get the playlist
    $playlistData = file_get_contents('../playlist.json');
    $playlists = json_decode($playlistData, true);

    // Get the video name
    $videoName = $_POST['videoName'];

    // Eliminar de la lista de reproducción
    $playlists = array_diff($playlists, array($videoName));
    // Do a list
    $playlists = array_values($playlists);

    // Save the playlist
    file_put_contents('../playlist.json', json_encode($playlists, JSON_PRETTY_PRINT));

    echo 'Video eliminado de la lista de reproducción.';
}else{
    echo 'No se recibió ningún nombre de video.';
    http_response_code(500);
    exit;
}