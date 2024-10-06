import sys
import pygame
import os

def play_mp3(file_path):
    pygame.init()
    pygame.mixer.init()

    if pygame.mixer.get_init() is None:
        print("Pygame mixer not initialized")
    else:
        print("Pygame mixer not initialized")

    if os.path.isfile(file_path):
        pygame.mixer.music.load(file_path)
        pygame.mixer.music.play()
        while pygame.mixer.music.get_busy():
            pygame.time.Clock().tick(10)
    else:
        print("File not found")

def main(argv):
    if len(argv) > 1:
        file_path = argv[1]
        play_mp3(file_path)

if __name__ == "__main__":
    main(sys.argv)