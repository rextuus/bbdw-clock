from PIL import Image

def image_to_uint8_hex_array(image_path, size=(32, 32)):
    # Open the image
    img = Image.open(image_path)

    # Resize the image to the desired size (32x32)
    img = img.resize(size)

    # Convert the image to RGB if it's not already
    img = img.convert('RGB')

    # Initialize an empty list to store the hex values
    rgb_hex_array = []

    # Iterate over each pixel in the image
    for y in range(size[1]):
        for x in range(size[0]):
            # Get the RGB values of the pixel
            r, g, b = img.getpixel((x, y))

            # Convert RGB to 24-bit integer (0xRRGGBB)
            hex_value = (r << 16) | (g << 8) | b
            rgb_hex_array.append(f'0x{hex_value:06X}')

    return rgb_hex_array

# Example usage
image_path = '13.jpg'
rgb_array = image_to_uint8_hex_array(image_path)

# Format the output as a C-style uint8_t array
output = "const uint8_t weather_icons[] PROGMEM={\n" + ','.join(rgb_array) + "\n};"

# Print the formatted output
print(output)
