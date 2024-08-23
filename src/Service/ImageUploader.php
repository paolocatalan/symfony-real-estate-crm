<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImageUploader
{
    public function __construct(
        private string $targetDirectory
    ) {
    }

    public function upload($imagePath): string
    {
        $fileName = uniqid() . '.' . $imagePath->guessExtension();

        try {
            $imagePath->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw new FileException($e->getMessage());
        }
        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}