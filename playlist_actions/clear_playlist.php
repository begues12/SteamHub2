<?php

// Save the playlist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    unlink('../playlist.json');
    file_put_contents('../playlist.json', json_encode([], JSON_PRETTY_PRINT));
} else {
    http_response_code(405);
}