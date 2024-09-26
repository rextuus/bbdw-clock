import asyncio
from gpiozero import Button
import requests
from concurrent.futures import ThreadPoolExecutor

# Define GPIO pins for the buttons
BUTTON_PIN_3 = 5
BUTTON_PIN_1 = 6
BUTTON_PIN_2 = 12

# Define API endpoints
API_URL_1 = "http://192.168.178.97/text?text=Hallo from button1&variant=static"
API_URL_2 = "http://192.168.178.97/text?text=Hallo from button2&variant=static"
API_URL_3 = "http://192.168.178.97/text?text=Hallo from button3&variant=static"

# Create Button instances
button_1 = Button(BUTTON_PIN_1)
button_2 = Button(BUTTON_PIN_2)
button_3 = Button(BUTTON_PIN_3)

# Function to make an API call
async def make_api_call(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            print(f"API call to {url} successful!")
        else:
            print(f"API call to {url} failed with status code: {response.status_code}")
    except Exception as e:
        print(f"Error making API call: {e}")

# Asynchronous button press handlers
async def button_1_pressed():
    print("Button 1 pressed!")
    await make_api_call(API_URL_1)

async def button_2_pressed():
    print("Button 2 pressed!")
    await make_api_call(API_URL_2)

async def button_3_pressed():
    print("Button 3 pressed!")
    await make_api_call(API_URL_3)

# Callback functions for buttons using the asyncio event loop
def button_1_callback(loop):
    asyncio.run_coroutine_threadsafe(button_1_pressed(), loop)

def button_2_callback(loop):
    asyncio.run_coroutine_threadsafe(button_2_pressed(), loop)

def button_3_callback(loop):
    asyncio.run_coroutine_threadsafe(button_3_pressed(), loop)

# Main function
async def main():
    # Get the current event loop
    loop = asyncio.get_running_loop()

    # Assign callback functions to buttons
    button_1.when_pressed = lambda: button_1_callback(loop)
    button_2.when_pressed = lambda: button_2_callback(loop)
    button_3.when_pressed = lambda: button_3_callback(loop)

    print("Listening for button presses...")

    # Keep the event loop running
    await asyncio.Event().wait()

# Run the asyncio event loop in the main thread
asyncio.run(main())
