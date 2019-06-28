<?php


namespace App\Service;


use Gaufrette\Adapter;
use Gaufrette\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoUploader
{
    function uploadFiles($files, Filesystem $fs) {
        /** @var UploadedFile $file */
        foreach ($files as $file) {

            $exifData = exif_read_data($file->getRealPath());

            dump($file->getFilename());
            dump($exifData);

            $fs->write($file->getClientOriginalName().".".$file->guessClientExtension(), file_get_contents($file->getRealPath()));
        }
    }
}