<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Asset\Context\RequestStackContext;

class UploaderHelper
{
    const POST_IMAGES = 'post_images';
    private $uploadsPath;
    public function __construct(string $uploadsPath, RequestStackContext $requestStackContext)
    {

        $this->uploadsPath = $uploadsPath;
        $this->requestStackContext = $requestStackContext;
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
    public function getPublicPath(string $path): string
    {
        return $this->requestStackContext
            ->getBasePath() . '/uploads/' . $path;
    }
}
