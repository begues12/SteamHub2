<?php
$host = "localhost"; // Dirección del servidor de sockets
$port = 9999; // El puerto donde se está ejecutando el servidor de sockets
$command = 1;

// Crear un socket TCP/IP
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    http_response_code(500);
    die();
}

$result = socket_connect($socket, $host, $port);
if ($result === false) {
    echo "Servidor offline";
    http_response_code(500);
    die();
}
// Enviar comandos al servidor de sockets
socket_write($socket, $command, strlen($command));

socket_close($socket);
?>
