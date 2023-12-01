let currentMode = 'playlist'; // Comenzar con 'random' como modo predeterminado

function togglePlaylistMode() {
    const modeButton = document.getElementById('toggleMode');
    const modeIcon = modeButton.querySelector('i');

    if (currentMode === 'random') {
        currentMode = 'loop';
        modeButton.classList.remove('btn-random');
        modeButton.classList.add('btn-static');
        modeIcon.classList.remove('fa-random');
        modeIcon.classList.add('fa-stop');
        showMessage('Modo de reproducción estática activado', 'success');
    } else if (currentMode === 'loop') {
        currentMode = 'playlist';
        modeButton.classList.remove('btn-static');
        modeButton.classList.add('btn-playlist');
        modeIcon.classList.remove('fa-stop');
        modeIcon.classList.add('fa-list');
        showMessage('Modo de reproducción de lista activado', 'success');
    } else {
        currentMode = 'random';
        modeButton.classList.remove('btn-playlist');
        modeButton.classList.add('btn-random');
        modeIcon.classList.remove('fa-list');
        modeIcon.classList.add('fa-random');
        showMessage('Modo de reproducción aleatoria activado', 'success');
    }

    send_action(currentMode); // Enviar el comando al servidor
}

function sendVideoQuality() {
    var quality = document.getElementById('videoQuality').value;
    send_action('quality ' + quality);
}

