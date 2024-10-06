<?php

declare(strict_types=1);

namespace App\File;

enum FileType: string
{
    case PDF = 'pdf';
    case IMAGE = 'image';
    case XML = 'xml';
    case AUDIO = 'audio';

    public static function fromExtension(string $extension): ?FileType
    {
        switch ($extension) {
            case 'pdf':
                return self::PDF;
            case 'jpg':
            case 'jpeg':
            case 'png':
                return self::IMAGE;
            case 'xml':
                return self::XML;
            case 'mp3':
                return self::AUDIO;
            default:
                return null;
        }
    }
}
