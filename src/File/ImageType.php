<?php

declare(strict_types=1);

namespace App\File;

enum ImageType: string
{
    case ALBUM_IMAGE = 'album_image';
    case LOOSE_IMAGE = 'loose_image';
}
