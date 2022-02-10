<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const POST_IMAGES = 'post_images';
    private $uploadsPath;
    public function __construct(string $uploadsPath)
    {

        $this->uploadsPath = $uploadsPath;
    }

    public function uploadPostImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath . '/' . self::POST_IMAGES;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );
        return $newFilename;
    }
}
