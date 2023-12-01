<?php
// Leer el archivo 'playlist'
$playlistData = file_get_contents('../playlist.json');
$playlists = json_decode($playlistData, true);

// Enviar los datos al cliente
echo json_encode($playlists);
?>