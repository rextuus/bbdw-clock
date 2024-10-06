import asyncio
from gpiozero import Button
import requests
import subprocess
import signal
from concurrent.futures import ThreadPoolExecutor

# Define GPIO pins for the buttons
BUTTON_PIN_1 = 5  # Existing button 3
BUTTON_PIN_3 = 6  # Existing button 1
BUTTON_PIN_2 = 12  # Existing button 2
BUTTON_PIN_4 = 16  # New stop button
BUTTON_PIN_5 = 20  # Another button (future functionality)

# Define API endpoints
API_URL_1 = "http://192.168.178.97/text?text=Hallo from button1&variant=static"
API_URL_2 = "http://192.168.178.97/text?text=Hallo from button2&variant=static"
API_URL_3 = "http://192.168.178.97/text?text=Hallo from button3&variant=static"
API_URL_4 = "http://192.168.178.97/text?text=Hallo from button4&variant=static"
API_URL_5 = "http://192.168.178.97/text?text=Hallo from button5&variant=static"

# Create Button instances
button_1 = Button(BUTTON_PIN_1)
button_2 = Button(BUTTON_PIN_2)
button_3 = Button(BUTTON_PIN_3)
button_4 = Button(BUTTON_PIN_4)  # New stop button
button_5 = Button(BUTTON_PIN_5)  # New button for future use

# Global variable for the audio player process
player_process = None

# Function to play audio using VLC
async def play_audio(file_path):
    global player_process
    if player_process is None:
        player_process = subprocess.Popen(['cvlc', '--play-and-exit', file_path], stdout=subprocess.PIPE, stderr=subprocess.PIPE)

# Function to stop audio
def stop_audio():
    global player_process
    if player_process is not None:
        player_process.send_signal(signal.SIGTERM)  # Terminate VLC process
        player_process = None

# Function to make an API call via led screen
async def make_api_call_led(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            print(f"API call to {url} successful!")
        else:
            print(f"API call to {url} failed with status code: {response.status_code}")
    except Exception as e:
        print(f"Error making API call: {e}")

async def make_api_call_symfony(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            print(f"API call to {url} successful!")
            data = response.json()
            return data.get('audio_path')  # Expecting the API to return the audio path
        else:
            print(f"API call to {url} failed with status code: {response.status_code}")
            return None
    except Exception as e:
        print(f"Error making API call: {e}")
        return None

# Asynchronous button press handlers
async def button_1_pressed():
    print("Button 1 pressed!")
    await make_api_call_led(API_URL_1)
    audio_path = await make_api_call_symfony(API_URL_1)
    if audio_path:
        await play_audio(audio_path)

async def button_2_pressed():
    print("Button 2 pressed!")
    await make_api_call_led(API_URL_2)
    audio_path = await make_api_call_symfony(API_URL_2)
    if audio_path:
        await play_audio(audio_path)

async def button_3_pressed():
    print("Button 3 pressed!")
    await make_api_call_led(API_URL_3)
    audio_path = await make_api_call_symfony(API_URL_3)
    if audio_path:
        await play_audio(audio_path)

async def button_4_pressed():
    print("Button 4 pressed!")
    await make_api_call_led(API_URL_4)
    audio_path = await make_api_call_symfony(API_URL_4)
    if audio_path:
        await play_audio(audio_path)

async def button_5_pressed():
    print("Button 5 pressed!")
    await make_api_call_led(API_URL_5)
    audio_path = await make_api_call_symfony(API_URL_5)
    if audio_path:
        await play_audio(audio_path)

# Button press handler for stop button
def button_stop_pressed():
    print("Stop button pressed!")
    stop_audio()

# Button press handler for the other button (no functionality yet)
def button_other_pressed():
    print("Other button pressed! (Functionality to be added later)")

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
    asyncio.run_coroutine_threadsafe(button_5_pressed(), loop)

# Main function
async def main():
    # Get the current event loop
    loop = asyncio.get_running_loop()

    # Assign callback functions to buttons
    button_1.when_pressed = lambda: button_1_callback(loop)
    button_2.when_pressed = lambda: button_2_callback(loop)
    button_3.when_pressed = lambda: button_3_callback(loop)
    button_4.when_pressed = lambda: button_4_callback(loop)
    button_5.when_pressed = lambda: button_5_callback(loop)
#     button_stop.when_pressed = button_stop_pressed  # Stop button handler
#     button_other.when_pressed = button_other_pressed  # Placeholder for future use

    print("Listening for button presses...")

    # Keep the event loop running
    await asyncio.Event().wait()

# Run the asyncio event loop in the main thread
asyncio.run(main())
