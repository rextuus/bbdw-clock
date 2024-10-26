#!/bin/bash

RELAY_PIN=18  # GPIO pin 18

# Function to set the GPIO pin
set_gpio() {
    echo "$1" > /sys/class/gpio/gpio$RELAY_PIN/value
}

# Export the GPIO pin if not already exported
if [ ! -e /sys/class/gpio/gpio$RELAY_PIN ]; then
    echo "$RELAY_PIN" > /sys/class/gpio/export
    sleep 1  # Wait for the GPIO to be exported
fi

# Set the GPIO direction to output
echo "out" > /sys/class/gpio/gpio$RELAY_PIN/direction

# Check the command argument
case "$1" in
    on)
        set_gpio 1  # Turn the relay ON
        echo "Relay is ON"
        ;;
    off)
        set_gpio 0  # Turn the relay OFF
        echo "Relay is OFF"
        ;;
    *)
        echo "Usage: $0 [on|off]"
        exit 1
        ;;
esac
