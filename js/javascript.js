
function show_loading() {
    $('#loadingIcon').css('display', 'flex');
    $('#loadingIcon').show();
}

function hide_loading() {
    $('#loadingIcon').css('display', 'none');
    $('#loadingIcon').hide();
}

$(document).ready(function(){

    show_loading();
    list_videos();
    calcularAltura();
    hide_loading();

    $('#uploadBtn').on('click', function() {
        $('#videoInput').click(); // Abre el selector de archivos
    });

    $('#videoInput').on('change', function() {
        var file = this.files[0];
        if (file) {
            var formData = new FormData();
            formData.append('videoFile', file);

            // Muestra el icono de carga
            show_loading();

            // Realiza la subida del archivo usando AJAX
            $.ajax({
                url: 'upload_video.php', // Ruta a tu script PHP de carga
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Oculta el icono de carga
                    list_videos();
                    hide_loading();
                    showMessage(response, 'success');
                    send_action('refresh');
                },
                error: function(response) {
                    // Oculta el icono de carga
                    hide_loading();
                    showMessage(response.responseText, 'error');
                }
            });
        }
    });

    $('#playlistIcon').click(function() {
        list_playlist();
        $('#playlistContainer').toggle(); // Muestra u oculta el contenedor de la playlist
    });

});

function calcularAltura() {
    var alturaTotal = window.innerHeight; // Altura total de la ventana

    var alturaHeader = document.getElementById('reproductor').offsetHeight; // Altura del header
    var alturaFooter = document.getElementById('botones').offsetHeight; // Altura del footer

    var alturaRestante = alturaTotal - (alturaHeader + alturaFooter + 20); // Altura restante

    document.getElementById('videoList').style.maxHeight = alturaRestante + 'px';
    document.getElementById('playlistContainer').style.maxHeight = alturaTotal - alturaFooter + 'px';
}

function list_videos()
{
    // Limpiar la lista de videos
    $('#videoList').html('');

    $.ajax({
        url: '/list_videos.php',
        type: 'GET',
        success: function(videos) {
            videos = JSON.parse(videos);
            for(var i in videos) {
                var videoItem = '' +
                '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                '<label class="truncate-text">' + videos[i] + '</label>' +
                    '<div class="btn-group" role="group" aria-label="Basic example">'
                        + '<button type="button" class="btn btn-outline-danger" onclick="delete_video(\'' + videos[i] + '\')"><i class="fas fa-trash"></i></button>'
                        + '<button type="button" class="btn btn-outline-primary"><a href="videos/' + videos[i] + '" download><i class="fas fa-download"></i></a></button>'
                        + '<button type="button" class="btn btn-outline-primary" onclick="add_playlist(\'' + videos[i] + '\')"><i class="fas fa-plus"></i></button>'
                        + '<button type="button" class="btn btn-outline-success" onclick="send_action(\'playvideo ' + videos[i] + '\')"><i class="fas fa-play"></i></button>'
                    '</div>' +
                '</li>';

                $('#videoList').append(videoItem);
            }
        },
        error: function() {
            alert('No se pudo obtener la lista de videos');
        }
    });
}

function showMessage(message, type, time=3000) {
    var messageBanner = $('#messageBanner');
    messageBanner.text(message);

    if (type === "success") {
        messageBanner.css("background-color", "#4CAF50"); // Verde para éxito
    } else if (type === "warning") {
        messageBanner.css("background-color", "#ff9800"); // Naranja para advertencia
    } else if (type === "error") {
        messageBanner.css("background-color", "#f44336"); // Rojo para error
    } else if (type === "info") {
        messageBanner.css("background-color", "#2196F3"); // Azul para información
    } else {
        messageBanner.css("background-color", "#555"); // Gris para otros casos
    }

    messageBanner.slideDown();

    // Ocultar el mensaje después de 3 segundos
    setTimeout(function() {
        messageBanner.slideUp();
    }, time);
}

function send_action(action)
{
    $.ajax({
        url: 'playlist_actions/send_action.php',
        type: 'POST',
        data: {
            'action': action
        },
        error: function(response) {
            showMessage(response.responseText, 'error');
        }
    });
}

function delete_video(video)
{
    $.ajax({
        url: 'playlist_actions/delete_video.php',
        type: 'POST',
        data: {
            'video': video
        },
        success: function(response) {
            showMessage(response, 'success');
            list_videos();
            send_action('refresh');
        },
        error: function(response) {
            showMessage(response.responseText, 'error');
        }
    });
}