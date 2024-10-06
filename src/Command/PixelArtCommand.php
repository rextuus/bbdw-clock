<?php

namespace App\Command;

use App\Discography\Content\Album\AlbumService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:pixel-art',
    description: 'Add a short description for your command',
)]
class PixelArtCommand extends Command
{
    public function __construct(private readonly AlbumService $albumService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO Deprecated was a test probably
        $album = $this->albumService->findById(1);


        $inputImage = 'assets/images/'.$album->getFile()->getRelativePath(); // Replace with your image path
        $outputImage = str_replace('jpg', 'png', $inputImage); // Replace with your output path
        dump($outputImage);
        $pixelSize = 16; // Adjust pixel size as needed
        $paletteSize = 16; // Adjust palette size as needed

        // Construct the command
        $command = escapeshellcmd("python3 convert_image_to_pixel_variant.py $inputImage $outputImage $pixelSize $paletteSize");


        // Execute the command and capture output and errors
        exec($command . ' 2>&1', $outputText, $returnVar);

        // Check if there was an error executing the command
        if ($returnVar !== 0) {
            $output->writeln('<error>Error executing Python script:</error>');
            $output->writeln($outputText); // Print the error output
            return Command::FAILURE;
        }

        // If the command was successful, display the output
        $output->writeln('<info>Pixel art version created successfully:</info>');
        $output->writeln($outputText);

        return Command::SUCCESS;
    }
}
