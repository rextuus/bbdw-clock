<?php

namespace App\Discography\Content\Album;

enum ReleaseType: string
{
    case SINGLE = 'single';
    case ALBUM = 'album';
    case OTHER = 'other';
}
