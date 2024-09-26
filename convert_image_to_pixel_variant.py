import argparse
from PIL import Image

def pixelate_image(input_path, output_path, pixel_size, palette_size):
    try:
        # Open the image
        img = Image.open(input_path)

        # Resize down for pixelation
        small = img.resize(
            (img.width // pixel_size, img.height // pixel_size),
            resample=Image.NEAREST
        )

        # Resize back to the original size
        result = small.resize(img.size, Image.NEAREST)

        # Reduce the color palette
        result = result.convert('P', palette=Image.ADAPTIVE, colors=palette_size)

        # Save the new image
        result.save(output_path)
        print(f"Saved pixel art version to {output_path}")
    except Exception as e:
        print(f"Error processing image: {e}")

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Convert an image to a pixel art variant.')
    parser.add_argument('input_path', type=str, help='Path to the input image.')
    parser.add_argument('output_path', type=str, help='Path where the output image will be saved.')
    parser.add_argument('--pixel_size', type=int, default=16, help='Size of the pixel blocks.')
    parser.add_argument('--palette_size', type=int, default=16, help='Number of colors in the palette.')

    args = parser.parse_args()

    pixelate_image(args.input_path, args.output_path, args.pixel_size, args.palette_size)
