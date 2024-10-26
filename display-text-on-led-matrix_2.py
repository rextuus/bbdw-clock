import asyncio
from gpiozero import Button
import requests
import subprocess
import signal
import os

# Define GPIO pins for the buttons
BUTTON_PIN_1 = 5  # Existing button 3
BUTTON_PIN_2 = 12  # Existing button 2
BUTTON_PIN_3 = 20  # Existing button 1
BUTTON_PIN_4 = 16  # New stop button
BUTTON_PIN_5 = 6  # Another button to stop the song

# Define API endpointsimport asyncio
from gpiozero import Button
import requests
import subprocess
import signal
import os

# Define GPIO pins for the buttons
BUTTON_PIN_1 = 5  # Existing button 3
BUTTON_PIN_2 = 12  # Existing button 2
BUTTON_PIN_3 = 20  # Existing button 1
BUTTON_PIN_4 = 16  # New stop button
BUTTON_PIN_5 = 0  # Another button to stop the song

# Define API endpoints
API_URL_1 = "http://localhost:8000/game/api/?buttonName=button_1"
API_URL_2 = "http://localhost:8000/game/api/?buttonName=button_2"
API_URL_3 = "http://localhost:8000/game/api/?buttonName=button_3"
# API_URL_1 = "http://192.168.178.97/text?text=Hallo from button1&variant=static"
# API_URL_2 = "http://192.168.178.97/text?text=Hallo from button2&variant=static"
# API_URL_3 = "http://192.168.178.97/text?text=Hallo from button3&variant=static"
API_URL_4 = "http://192.168.178.97/text?text=Hallo from button4&variant=static"
# API_URL_5 = "http://192.168.178.97/text?text=Hallo from button5&variant=static"

# Create Button instances
button_1 = Button(BUTTON_PIN_1)
button_2 = Button(BUTTON_PIN_2)
button_3 = Button(BUTTON_PIN_3)
button_4 = Button(BUTTON_PIN_4)  # New stop button
button_5 = Button(BUTTON_PIN_5)  # Button for stopping the song (same as button_4)

# Global variable for the audio player process
player_process = None

# Function to play audio using VLC without blocking
async def play_audio(file_path):
    global player_process
    if os.path.exists(file_path):  # Check if file exists
        if player_process is None:
            try:
                # Start VLC process asynchronously
                player_process = subprocess.Popen(['cvlc', '--play-and-exit', file_path], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
                print(f"Playing audio: {file_path}")
            except Exception as e:
                print(f"Error starting VLC process: {e}")
                player_process = None
    else:
        print(f"File '{file_path}' does not exist.")

# Function to stop audio
def stop_audio():
    global player_process
    if player_process is not None:
        print("Stopping audio...")
        player_process.terminate()  # Terminate VLC process
        player_process = None

# Function to make an API call via LED screen
async def make_api_call_led(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            print(f"API call to {url} successful!")
        else:
            print(f"API call to {url} failed with status code: {response.status_code}")
    except Exception as e:
        print(f"Error making API call: {e}")

# Function to make an API call to the Symfony app
async def make_api_call_symfony(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            print(f"API call to {url} successful!")
            data = response.json()
            print(data)
            return "/home/pi/bbdw-clock/data/songs/" + data.get('audio_path')  # Ensure the path is correct
        else:
            print(f"API call to {url} failed with status code: {response.status_code}")
            return None
    except Exception as e:
        print(f"Error making API call: {e}")
        return None

# Asynchronous button press handlers
async def button_1_pressed():
    print("Button 1 pressed!")
    audio_path = await make_api_call_symfony(API_URL_1)
    print(f"Attempting to play audio from: {audio_path}")  # Debug print statement
    if audio_path and os.path.exists(audio_path):  # Ensure file exists
        await play_audio(audio_path)
    else:
        print(f"Audio file does not exist or invalid path: {audio_path}")

async def button_2_pressed():
    print("Button 2 pressed!")
    audio_path = await make_api_call_symfony(API_URL_2)
    print(f"Attempting to play audio from: {audio_path}")
    if audio_path and os.path.exists(audio_path):
        await play_audio(audio_path)
    else:
        print(f"Audio file does not exist or invalid path: {audio_path}")

async def button_3_pressed():
    print("Button 3 pressed!")
    audio_path = await make_api_call_symfony(API_URL_3)
    print(f"Attempting to play audio from: {audio_path}")
    if audio_path and os.path.exists(audio_path):
        await play_audio(audio_path)
    else:
        print(f"Audio file does not exist or invalid path: {audio_path}")

async def button_4_pressed():
    print("Button 4 pressed!")
    audio_path = await make_api_call_symfony(API_URL_4)
    print(f"Attempting to play audio from: {audio_path}")
    if audio_path and os.path.exists(audio_path):
        await play_audio(audio_path)
    else:
        print(f"Audio file does not exist or invalid path: {audio_path}")

# Button press handler for stop button (same for both button_4 and button_5)
def button_stop_pressed():
    print("Stop button pressed!")
    stop_audio()

# Callback functions for buttons using the asyncio event loop
def button_1_callback(loop):
    asyncio.run_coroutine_threadsafe(button_1_pressed(), loop)

def button_2_callback(loop):
    asyncio.run_coroutine_threadsafe(button_2_pressed(), loop)

def button_3_callback(loop):
    asyncio.run_coroutine_threadsafe(button_3_pressed(), loop)

def button_4_callback(loop):
    asyncio.run_coroutine_threadsafe(button_4_pressed(), loop)

def button_5_callback(loop):
    button_stop_pressed()  # Button 5 now stops the audio

async def main():
    loop = asyncio.get_running_loop()
    button_1.when_pressed = lambda: button_1_callback(loop)
    button_2.when_pressed = lambda: button_2_callback(loop)
    button_3.when_pressed = lambda: button_3_callback(loop)
    button_4.when_pressed = lambda: button_4_callback(loop)
    button_5.when_pressed = lambda: button_5_callback(loop)  # Use stop_audio when button 5 is pressed
    print("Listening for button presses...")
    await asyncio.Event().wait()

# Start the async event loop
asyncio.run(main())

API_URL_1 = "http://localhost:8000/game/api/?buttonName=button_1"
API_URL_2 = "http://localhost:8000/game/api/?buttonName=button_2"
API_URL_3 = "http://localhost:8000/game/api/?buttonName=button_3"
# API_URL_1 = "http://192.168.178.97/text?text=Hallo from button1&variant=static"
# API_URL_2 = "http://192.168.178.97/text?text=Hallo from button2&variant=static"
# API_URL_3 = "http://192.168.178.97/text?text=Hallo from button3&variant=static"
API_URL_4 = "http://192.168.178.97/text?text=Hallo from button4&variant=static"
# API_URL_5 = "http://192.168.178.97/text?text=Hallo from button5&variant=static"

# Create Button instances
button_1 = Button(BUTTON_PIN_1)
button_2 = Button(BUTTON_PIN_2)
button_3 = Button(BUTTON_PIN_3)
button_4 = Button(BUTTON_PIN_4)  # New stop button
button_5 = Button(BUTTON_PIN_5)  # Button for stopping the song (same as button_4)

# Global variable for the audio player process
player_process = None

# Function to play audio using VLC without blocking
async def play_audio(file_path):
    global player_process
    if os.path.exists(file_path):  # Check if file exists
        if player_process is None:
            try:
                # Start VLC process asynchronously
                player_process = subprocess.Popen(['cvlc', '--play-and-exit', file_path], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
                print(f"Playing audio: {file_path}")
            except Exception as e:
                print(f"Error starting VLC process: {e}")
                player_process = None
    else:
        print(f"File '{file_path}' does not exist.")

# Function to stop audio
def stop_audio():
    global player_process
    if player_process is not None:
        print("Stopping audio...")
        player_process.terminate()  # Terminate VLC process
        player_process = None

# Function to make an API call via LED screen
async def make_api_call_led(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            print(f"API call to {url} successful!")
        else:
            print(f"API call to {url} failed with status code: {response.status_code}")
    except Exception as e:
        print(f"Error making API call: {e}")

# Function to make an API call to the Symfony app
async def make_api_call_symfony(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            print(f"API call to {url} successful!")
            data = response.json()
            print(data)
            return "/home/pi/bbdw-clock/data/songs/" + data.get('audio_path')  # Ensure the path is correct
        else:
            print(f"API call to {url} failed with status code: {response.status_code}")
            return None
    except Exception as e:
        print(f"Error making API call: {e}")
        return None

# Asynchronous button press handlers
async def button_1_pressed():
    print("Button 1 pressed!")
    audio_path = await make_api_call_symfony(API_URL_1)
    print(f"Attempting to play audio from: {audio_path}")  # Debug print statement
    if audio_path and os.path.exists(audio_path):  # Ensure file exists
        await play_audio(audio_path)
    else:
        print(f"Audio file does not exist or invalid path: {audio_path}")

async def button_2_pressed():
    print("Button 2 pressed!")
    audio_path = await make_api_call_symfony(API_URL_2)
    print(f"Attempting to play audio from: {audio_path}")
    if audio_path and os.path.exists(audio_path):
        await play_audio(audio_path)
    else:
        print(f"Audio file does not exist or invalid path: {audio_path}")

async def button_3_pressed():
    print("Button 3 pressed!")
    audio_path = await make_api_call_symfony(API_URL_3)
    print(f"Attempting to play audio from: {audio_path}")
    if audio_path and os.path.exists(audio_path):
        await play_audio(audio_path)
    else:
        print(f"Audio file does not exist or invalid path: {audio_path}")

async def button_4_pressed():
    print("Button 4 pressed!")
    audio_path = await make_api_call_symfony(API_URL_4)
    print(f"Attempting to play audio from: {audio_path}")
    if audio_path and os.path.exists(audio_path):
        await play_audio(audio_path)
    else:
        print(f"Audio file does not exist or invalid path: {audio_path}")

# Button press handler for stop button (same for both button_4 and button_5)
def button_stop_pressed():
    print("Stop button pressed!")
    stop_audio()

# Callback functions for buttons using the asyncio event loop
def button_1_callback(loop):
    asyncio.run_coroutine_threadsafe(button_1_pressed(), loop)

def button_2_callback(loop):
    asyncio.run_coroutine_threadsafe(button_2_pressed(), loop)

def button_3_callback(loop):
    asyncio.run_coroutine_threadsafe(button_3_pressed(), loop)

def button_4_callback(loop):
    asyncio.run_coroutine_threadsafe(button_4_pressed(), loop)

def button_5_callback(loop):
    button_stop_pressed()  # Button 5 now stops the audio

async def main():
    loop = asyncio.get_running_loop()
    button_1.when_pressed = lambda: button_1_callback(loop)
    button_2.when_pressed = lambda: button_2_callback(loop)
    button_3.when_pressed = lambda: button_3_callback(loop)
    button_4.when_pressed = lambda: button_4_callback(loop)
    button_5.when_pressed = lambda: button_5_callback(loop)  # Use stop_audio when button 5 is pressed
    print("Listening for button presses...")
    await asyncio.Event().wait()

# Start the async event loop
asyncio.run(main())
