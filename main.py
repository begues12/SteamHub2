import cv2
import os
import random
import socket
import threading
import json
from screeninfo import get_monitors
import numpy as np
import cv2

class VideoPlayer:
    def __init__(self, folder_path):
        self.folder_path = folder_path
        self.playlists = []
        self.video_files = [f for f in os.listdir(folder_path)]
        self.current_video_index = 0
        self.is_playing = True
        self.is_loop = False
        self.is_random = False
        self.is_fullscreen = False
        self.passVideo = False
        self.target_width = 0
        self.target_height = 0
        self.screen_height = get_monitors()[0].height
        self.screen_width = get_monitors()[0].width

    def play_video(self):
        self.update_playlist()
        cv2.namedWindow('Video', cv2.WND_PROP_FULLSCREEN)
        cv2.setWindowProperty('Video', cv2.WND_PROP_FULLSCREEN, cv2.WINDOW_FULLSCREEN)

        # Fullscreen size
        self.target_width = cv2.getWindowImageRect('Video')[2]
        self.target_height = cv2.getWindowImageRect('Video')[3]
        
        while True:
            
            # If video file is not empty
            if len(self.video_files) == 0:
                print("No hay videos en la carpeta")
                continue
            
            video_path = os.path.join(self.folder_path, self.video_files[self.current_video_index])            
            cap = cv2.VideoCapture(video_path)

            original_width = cap.get(cv2.CAP_PROP_FRAME_WIDTH)
            original_height = cap.get(cv2.CAP_PROP_FRAME_HEIGHT)

            # floar division zero error
            if original_width == 0 or original_height == 0:
                continue
            else:
                original_width = int(original_width)
                original_height = int(original_height)
                
            scale_width = self.screen_width / original_width
            scale_height = self.screen_height / original_height
            scale = min(scale_width, scale_height)

            new_width = int(original_width * scale)
            new_height = int(original_height * scale)

            x_offset = int((self.screen_width - new_width) / 2)
            y_offset = int((self.screen_height - new_height) / 2)
            ret, first_frame = cap.read()
            if ret:
                resized_first_frame = cv2.resize(first_frame, (new_width, new_height))
                frame_with_borders = np.zeros((self.screen_height, self.screen_width, 3), dtype=np.uint8)
                frame_with_borders[y_offset:y_offset+new_height, x_offset:x_offset+new_width] = resized_first_frame
            
            while cap.isOpened():
                
                if self.passVideo:
                    self.passVideo = False
                    break
                
                if self.is_playing:
                    ret, frame = cap.read()
                    if not ret:
                        print("No se pudo leer el frame, finalizando reproducción.")
                        break
                    # Change the quality of the video
                    resized_frame = cv2.resize(frame, (new_width, new_height))
                    frame_with_borders = np.zeros((self.screen_height, self.screen_width, 3), dtype=np.uint8)
                    frame_with_borders[y_offset:y_offset+new_height, x_offset:x_offset+new_width] = resized_frame

                    if ret:
                        cv2.imshow('Video', frame_with_borders)
                        self.handle_key_press(cv2.waitKey(5))
                    else:
                        break
                else:
                    self.handle_key_press(cv2.waitKey(25))
                                    
            if self.is_loop:
                continue
            elif self.is_random:
                self.current_video_index = random.randint(0, len(self.video_files) - 1)
            else:
                self.current_video_index += 1
                
                if self.current_video_index >= len(self.video_files):
                    self.current_video_index = 0
                elif self.current_video_index < 0:
                    self.current_video_index = len(self.video_files) - 1            
            
            print("va a reproducir el video: " + self.video_files[self.current_video_index])

            cap.release()

    def handle_key_press(self, key):
        if key & 0xFF == ord('q'):
            cv2.destroyAllWindows()
            exit(0)
        elif key & 0xFF == ord('p'):
            self.toggle_play_pause()
        elif key & 0xFF == ord('f'):
            self.toggle_fullscreen()

    def toggle_play_pause(self):
        self.is_playing = not self.is_playing

    def toggle_fullscreen(self):
        self.is_fullscreen = not self.is_fullscreen
        if self.is_fullscreen:
            cv2.setWindowProperty('Video', cv2.WND_PROP_FULLSCREEN, cv2.WINDOW_FULLSCREEN)
        else:
            cv2.setWindowProperty('Video', cv2.WND_PROP_FULLSCREEN, cv2.WINDOW_NORMAL)
    
    def update_playlist(self):
        # Volver a cargar la lista de videos playlist.json
        f = open("playlist.json", "r")
        print(f.read())
        if f.read() == '':
            [f for f in os.listdir(self.folder_path)]
        else:
            # json is a list
            print(json.loads(f.read()))
            self.playlists = json.loads(f.read())
            
        f.close()
        
    def start_socket_server(self):
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.server.bind(('localhost', 9999))  # Ajusta según sea necesario
        self.server.listen(5)
        print("Servidor de sockets iniciado, esperando conexiones...")

        while True:
            client_socket, addr = self.server.accept()
            print(f"Conexión establecida con {addr}")
            client_handler = threading.Thread(target=self.handle_client, args=(client_socket,))
            client_handler.start()

    def handle_client(self, client_socket):
        while True:
            command = client_socket.recv(1024).decode('utf-8')
            if command:
                print(f"Recibido: {command}")
                self.process_command(command)
            else:
                break
        client_socket.close()
    
    def process_command(self, command):
        print(f"Comando recibido: {command}")
        if command == 'pause':
            self.toggle_play_pause()
        elif command == 'fullscreen':
            self.toggle_fullscreen()
        elif command == 'next':
            self.current_video_index += 1
            self.passVideo = True
        elif command == 'prev':
            self.current_video_index -= 2
            self.passVideo = True
        elif command == 'loop':
            self.is_loop = not self.is_loop
            self.is_random = False
        elif command == 'random':
            self.is_random = not self.is_random 
            self.is_loop = False
        elif command == 'playlist':
            self.is_random = False
            self.is_loop = False
        elif command.startswith('playvideo'):
            video = command.split(' ')[1]
            if video not in self.playlists:
                self.playlists.append(video)
            if video not in self.video_files:
                self.video_files.append(video)
                
            self.current_video_index = self.video_files.index(video) - 1
            self.passVideo = True
            # If is not in the list, add it
        elif command == 'refresh':
            # Add logic refresh all
            if video not in self.playlists:
                self.playlists.append(video)
            if video not in self.video_files:
                self.video_files.append(video)
                
        elif command.startswith('updateplaylist'):    
            self.update_playlist() 
        elif command.startswith('quality'):
            self.current_quality = command.split(' ')[1]
            self.current_quality = self.current_quality.lower()
            
# Uso del reproductor
player = VideoPlayer('videos')
threading.Thread(target=player.start_socket_server).start()
player.play_video()