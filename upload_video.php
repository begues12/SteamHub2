<?php
if (isset($_FILES['videoFile'])) {
    $videoFile = $_FILES['videoFile'];

    // Validaciones
    $uploadDir = 'videos/'; // Asegúrate de que este directorio existe y tiene permisos de escritura
    if (is_dir($uploadDir)) {

        if (!is_writable($uploadDir)) {
            echo 'El directorio de carga no tiene permisos de escritura.';
            http_response_code(500);
            exit;
        }

        // If name has spaces, replace with underscores
        $videoFile['name'] = str_replace(' ', '_', $videoFile['name']);

        // Si existe el archivo, renombrarlo
        if (file_exists($uploadDir . $videoFile['name'])) {
            echo 'El archivo ya existe';
            http_response_code(500);
            exit;
        }

        $uploadPath = $uploadDir . basename($videoFile['name']);
        $fileType = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));
    }else{
        echo 'El directorio de carga no existe.';
        http_response_code(500);
        exit;
    }

    // Verificar si el archivo es realmente un video
    // Puedes ampliar esta lista con otros formatos de video
    $allowedTypes = ['mp4', 'avi', 'mov', 'mpeg', 'wmv'];
    if (!in_array($fileType, $allowedTypes)) {
        echo 'Archivo no permitido. Los formatos válidos son: MP4, AVI, MOV, MPEG, WMV.';
        http_response_code(500);
        exit;
    }

    // Verificar el tamaño del archivo (por ejemplo, máx. 50MB)
    // Upload_max en MB
    $maxSize = intval(ini_get('upload_max_filesize')) * 1024 * 1024;
    if ($videoFile['size'] > $maxSize) {
        echo 'El archivo es demasiado grande. El tamaño máximo permitido es de' . $maxSize/ 1024 / 1024 . 'MB.';
        http_response_code(500);
        exit;
    }

    // Intentar guardar el archivo
    if (move_uploaded_file($videoFile['tmp_name'], $uploadPath)) {
        // Add to json playlist
        $playlist = json_decode(file_get_contents('playlist.json'), true);
        // Is a list only add in end
        if (isset($_POST['list'])) {
            $playlist[] = $videoFile['name'];
        } else {
            // Is a single video, replace
            $playlist = [$videoFile['name']];
        }
        file_put_contents('playlist.json', json_encode($playlist));
        
        echo 'Archivo guardado con éxito.';
    } else {
        echo 'Hubo un error al subir el archivo.';
        http_response_code(500);
        exit;
    }
} else {
    echo 'No se recibió ningún archivo.';
    http_response_code(500);
    exit;
}

?>
