<?php
$directory = 'videos';
// Si existe el directorio

if (!is_dir($directory)) {
    // Lo creamos
    mkdir($directory, 0777, true);
}

$videos = array_diff(scandir($directory), array('..', '.'));
echo json_encode($videos);
?>