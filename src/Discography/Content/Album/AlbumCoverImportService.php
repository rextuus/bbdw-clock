<?php

declare(strict_types=1);

namespace App\Discography\Content\Album;

use App\Discography\Content\Album\Data\AlbumData;
use App\Discography\Import\Parser\Parser;
use App\Entity\Album;
use App\File\FileService;
use App\File\Flysystem\FilesystemProvider;
use App\File\ImageType;
use function Symfony\Component\String\s;

class AlbumCoverImportService
{
    public function __construct(
        private readonly Parser $parser,
        private readonly FileService $fileService,
        private readonly AlbumService $albumService,
    ) {
    }

    public function importCoverForAlbum(Album $album): void
    {
        // get image
        $image = $this->parser->crawlAlbumImage($album->getCoverImageUrl());
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $image);
        rewind($stream);

        // store image
        $fileName = s($album->getName())->snake()->lower() . '_cover';

        $file = $this->fileService->importBinaryFileIntoFilesystem(
            $fileName,
            'jpg',
            FilesystemProvider::IDENT_ALBUM,
            $stream,
            ImageType::ALBUM_IMAGE
        );

        $albumData = AlbumData::initFromEntity($album);
        $albumData->setCover($file);

        $this->albumService->update($album, $albumData);
    }
}
