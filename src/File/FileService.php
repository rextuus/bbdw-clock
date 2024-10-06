<?php

declare(strict_types=1);

namespace App\File;

use App\Entity\File;
use App\File\Data\FileData;
use App\File\Flysystem\FilesystemProvider;
use Exception;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpKernel\KernelInterface;

class FileService
{
    public function __construct(
        private readonly FileRepository $repository,
        private readonly FileFactory $factory,
        private readonly FilesystemProvider $filesystemProvider,
        private readonly FilePathGenerator $filePathGenerator,
        private KernelInterface $kernel
    ) {
    }

    public function createByData(FileData $data, bool $flush = false): File
    {
        $file = $this->factory->createFile($data);
        $this->repository->persist($file);

        if ($flush) {
            $this->repository->flush();
        }

        return $file;
    }

    public function update(File $file, ?FileData $data, bool $flush = false): File
    {
        if ($data) {
            $this->factory->mapFile($file, $data);
        }

        if ($flush) {
            $this->repository->flush();
        }

        return $file;
    }

    public function storeFileFromBinaryString(): void
    {
    }

    public function read(File $file): string
    {
        $filesystem = $this->filesystemProvider->getFilesystem($file->getFilesystemIdent());

        return $filesystem->read($file->getRelativePath());
    }

    public function readByFileGenerationResult(FileGenerationResult $result): string
    {
        $filesystem = $this->filesystemProvider->getFilesystem($result->getFilesystemIdent());

        return $filesystem->read($result->getFullFilePath());
    }

    /**
     * @return resource
     */
    public function readStream(File $file)
    {
        $filesystem = $this->filesystemProvider->getFilesystem($file->getFilesystemIdent());

        return $filesystem->readStream($file->getRelativePath());
    }

    public function importBinaryFileIntoFilesystem(
        string $fileName,
        string $extension,
        FileType $fileType,
        string $filesystemIdent,
        $content,
        ?ImageType $imageType = null
    ): File {
        $hashedFolderName = $this->filePathGenerator->generateRandomFilePath();

        $filePath = sprintf(
            '%s/%s.%s',
            $hashedFolderName,
            $fileName,
            $extension,
        );

        $filesystem = $this->filesystemProvider->getFilesystem($filesystemIdent);
        $filesystem->writeStream($filePath, $content);

        $fileGenerationResult = FileGenerationResult::create($filePath, $hashedFolderName, $fileName, $filesystemIdent);

        return $this->createFileEntityByGenerationResult(
            $fileGenerationResult,
            $fileType,
            $extension,
            true,
            $imageType
        );
    }

    public function getFullQualifiedPath(File $file)
    {
        $filesystem = $this->filesystemProvider->getFilesystem($file->getFilesystemIdent());

        // For local file system, you normally have base directory.
        $baseDir = $this->kernel->getProjectDir();

        // `$file->getRelativePath()` should return the path relative to the `$baseDir`.
        $relativePath = $file->getRelativePath();

        // So here is the full path.
        return $baseDir . '/assets/songs/' . $relativePath;
    }

    private function deleteFileFromFilesystem(string $filesystemIdent, string $relativePath): void
    {
        $filesystem = $this->filesystemProvider->getFilesystem($filesystemIdent);
        $filesystem->delete($relativePath);
    }

    public function createFileEntityByGenerationResult(
        FileGenerationResult $result,
        FileType $fileType,
        string $extension,
        bool $flush,
        ImageType $imageType = null
    ): File {
        $fileData = (new FileData())
            ->setFilesystemIdent($result->getFilesystemIdent())
            ->setPath($result->getDirectoryPath())
            ->setName($result->getFileName())
            ->setExtension($extension)
            ->setImageType($imageType)
            ->setType($fileType);

        return $this->createByData($fileData, $flush);
    }
}
