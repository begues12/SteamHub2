<?php

// Delete video from folder and playlist
if ($_POST['video'])
{
    $video = $_POST['video'];

    $playlist = json_decode(file_get_contents('../playlist.json'), true);

    // Delete from playlist
    $key = array_search($video, $playlist);
    if ($key !== false) {
        unset($playlist[$key]);
    }

    // Delete from folder if cant delete 
    if (!unlink('../videos/' . $video)) {
        echo 'No se pudo eliminar el archivo.';
        http_response_code(500);
        exit;
    }
    // Save playlist
    file_put_contents('playlist.json', json_encode($playlist));

    echo 'Archivo eliminado con éxito.';
}else{
    echo 'No se ha especificado el archivo a eliminar.';
    http_response_code(500);
    exit;
}