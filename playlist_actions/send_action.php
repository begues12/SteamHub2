<?php
$host = "localhost"; // Dirección del servidor de sockets
$port = 9999; // El puerto donde se está ejecutando el servidor de sockets
$command = "";
if (isset($_POST["action"]))
{   
    $command = $_POST['action'];
}else{
    echo "Error al recibir el comando";
    http_response_code(400);
    die();
}

// Crear un socket TCP/IP
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "error al crear el socket";
    http_response_code(500);
    die();
}

$result = socket_connect($socket, $host, $port);
if ($result === false) {
    echo "error al conectarse al servidor";
    http_response_code(500);
    die();
}
// Enviar comandos al servidor de sockets
socket_write($socket, $command, strlen($command));
echo "Comando enviado";


socket_close($socket);
?>
