
function list_playlist()
{
    // Delete .list-group-item in playlist
    $('#playlist').find('.list-group-item').remove();

    $.ajax({
        url: 'playlist_actions/get_playlists.php',
        type: 'GET',
        dataType: 'json',
        success: function(playlists) {
            if (Array.isArray(playlists)) {
                playlists.forEach(function(video) {
                    // Crear y añadir la lista de reproducción al contenedor
                    var html = '<li class="list-group-item  d-flex justify-content-between align-items-center p-1" draggable="true" ondragstart="dragStart(event)" ondragover="dragOver(event)" ondrop="drop(event)">' +
                                    '<label class="truncate-text mr-auto">' + video + '</label>' +
                                    '<button type="button" class="btn btn-outline-danger m-0 mr-2 deletePlaylist" data-video="' + video + '" onclick="delete_video_playlist(this)"><i class="fas fa-trash m-0"></i></button>' +
                                '</li>';
                    $('#playlist').append(html);
                });
                send_action('updateplaylist');
            }else{
                alert('No se pudo obtener la lista de reproducción');
                console.log(playlists);
            }
        },
        error: function() {
            alert('No se pudo obtener la lista de videos');
        }
    });

}


function add_playlist(videoName) {
    $.ajax({
        url: 'playlist_actions/add_to_playlist.php',
        type: 'POST',
        data: {
            videoName: videoName
        },
        success: function(response) {
            showMessage(response, 'success');
            list_playlist();
        },
        error: function(response) {
            console.log(response);
            showMessage(response.responseText, 'error');
            list_playlist();
        }
    });
}

function delete_video_playlist(video) {  

    // Show all data
    console.log($(video).data('video'));

    $.ajax({
        url: 'playlist_actions/del_to_playlist.php',
        type: 'POST',
        data: {
            videoName: $(video).data('video')
        },
        success: function(response) {
            // Delete parent element
            $(video).parent().remove();
            showMessage(response, 'success');
            list_videos();
        },
        error: function(response) {
            showMessage(response, 'error');
        }
    });
}

function clear_playlist() {
    $.ajax({
        url: 'playlist_actions/clear_playlist.php',
        type: 'POST',
        success: function(response) {
            showMessage(response, 'success');
            list_playlist();
        },
        error: function(response) {
            showMessage(response, 'error');
        }
    });
}

function toggleEditMode() {
    isInEditMode = !isInEditMode;
    if (isInEditMode) {
        activeEditMode();
        showMessage('Modo edición activado', 'info');
    } else {
        desactiveEditMode();
        showMessage('Modo edición desactivado', 'info');
    }
}

function desactiveEditMode() {
    isInEditMode = false;
    $('.deletePlaylist').fadeIn();
    document.querySelectorAll('#playlist .list-group-item').forEach(item => {
        item.removeEventListener('mousedown', dragStart);
        item.removeEventListener('touchstart', dragStart);
    });
}

function activeEditMode() {
    isInEditMode = true;
    $('.deletePlaylist').fadeOut();
    document.querySelectorAll('#playlist .list-group-item').forEach(item => {
        item.addEventListener('mousedown', dragStart);
        item.addEventListener('touchstart', dragStart);
    });
}
