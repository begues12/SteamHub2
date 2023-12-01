<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video Playlist and Upload</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="api/jquery-3.5.1.min.js"></script>
    <script src="api/bootstrap.min.js"></script>
    <link rel="stylesheet" href="api/jquery-ui.css">
    <link rel="stylesheet" href="api/fontawesome/css/all.css">
    <link rel="stylesheet" href="api/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div id="loadingIcon" class="justify-content-center align-items-center text-white" style="height: 100vh; background-color: rgba(0, 0, 0, 0.7); position: fixed; top: 0; left: 0; width: 100vw; z-index: 9999; display: none;">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
        </div>

        <div class="m-3">

            <div class="d-flex justify-content-end align-items-center">
                <div class="rounded-circle bg-danger" style="width: 10px; height: 10px;" id='status_circle'></div>
                <label class="ml-2" id='status_text'>Offline</label>
            </div>
        </div>

        <div class="mb-3">
            <div class="row justify-content-between" id="botones">
                <div class="col d-flex  mb-3 mt-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal" onclick="list_videos()" id="uploadBtn">
                        <i class="fas fa-upload"></i>
                    </button>
                    <input type="file" id="videoInput" accept="video/*" style="display: none;">

                </div>
                <div class="col d-flex flex-row-reverse mb-3 mt-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal" onclick="list_videos()">
                        <i class="fas fa-redo-alt"></i>
                    </button>
                </div>
            </div>
            <ul class="list-group" id="videoList" style="max-height: 500px; overflow-y: auto;">
            </ul>
        </div>
        <nav class="navbar fixed-bottom navbar-light" id="reproductor">
            <select id="videoQuality" onchange="sendVideoQuality()">
                <option value="480p">480p</option>
                <option value="720p">720p</option>
                <option value="1080p">1080p</option>
            </select>
            <div class="container-fluid justify-content-center">
                <button class="btn btn-outline-primary mx-1" id="toggleMode" onclick="togglePlaylistMode()"><i class="fas fa-random fa-1x"></i></button>
                <button class="btn btn-outline-danger mx-1" onclick="previousTrack()"><i class="fas fa-font fa-1x"></i></button>
                <button class="btn btn-outline-primary mx-1" onclick="send_action('prev')"><i class="fas fa-backward fa-1x"></i></button>
                <button class="btn btn-outline-success mx-1" onclick="send_action('pause')"><i class="fas fa-play fa-2x"></i></button>
                <button class="btn btn-outline-primary mx-1" onclick="send_action('next')"><i class="fas fa-forward fa-1x"></i></button>
                <button class="btn btn-outline-danger mx-1" id="playlistIcon"><i class="fas fa-list fa-1x"></i></button>
            </div>
        </nav>
        <div id="messageBanner"></div>
    

        <!-- Close button -->
        <div class="container-fluid bg-white" id="playlistContainer">
            <div class="d-flex justify-content-between">
                <h5 class="text-dark">Playlist</h5>
                <button type="button" class="btn btn-outline-transparent" onclick="$('#playlistContainer').hide();"><i class="fas fa-times"></i></button>
            </div>
            <!-- Action buttons -->
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-primary m-2" onclick="clear_playlist()"><i class="fas fa-trash"></i></button>
                <button type="button" class="btn btn-outline-primary m-2" onclick="toggleEditMode()"><i class="fas fa-edit"></i></button>
            </div>

            <ul class="list-group pr-4" id="playlist" style="max-height: 500px; overflow-y: auto;">
            </ul>
        </div>
    </div>


    <link rel="stylesheet" href="css.css">
    <script src="js/javascript.js"></script>
    <script src="js/playlist.js"></script>
    <script src="js/drag.js"></script>
    <script src="js/button_action.js"></script>
    <script src="js/status.js"></script>
</body>
</html>