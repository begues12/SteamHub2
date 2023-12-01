<?php

if ($_POST['videoName'])
{

    // Get the playlist
    $playlistData = file_get_contents('../playlist.json');
    $playlists = json_decode($playlistData, true);

    // Get the video name
    $videoName = $_POST['videoName'];

    if (in_array($videoName, $playlists))
    {
        echo 'El video ya está en la lista de reproducción.';
        http_response_code(500);
        exit;
    }

    // Add to the playlist
    $playlists[] = $videoName;

    // Save the playlist
    file_put_contents('../playlist.json', json_encode($playlists, JSON_PRETTY_PRINT));

    echo 'Video agregado a la lista de reproducción.';
}else{
    echo 'No se recibió ningún nombre de video.';
    http_response_code(500);
    exit;
}