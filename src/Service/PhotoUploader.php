<?php


namespace App\Service;


use DateTime;
use Exception;
use Gaufrette\Adapter;
use Gaufrette\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoUploader
{

    function uploadFile(UploadedFile $file, Filesystem $fs) {
        $fileStatuses = array();
        $savedFiles = array();

        $exifData = exif_read_data($file->getRealPath());


        //dump($exifData);

        if(explode("/", $exifData['MimeType'])[0] !== "image") {
            $fileStatuses[$file->getClientOriginalName()] = "errorInvalidMime";
        }

        try {

            /** @var DateTime $fileTime */
            $fileTime = new DateTime($exifData['DateTimeDigitized']);
        } catch (Exception $e) {
            $fileTime = false;
        }


        $filename = "";

        if($fileTime === false || $fileTime === @$fileTime->getTimestamp()) {
            $filename .= "timeless/";
        } else {
            $filename .= $fileTime->format("Y")."-";
            $filename .= $fileTime->format("m")."-";
            $filename .= $fileTime->format("d")."_";
            $filename .= $fileTime->format("H-i-s")."_";
        }

        $filename .= $exifData["Make"] ?? "NoMake";
        $filename .= "_";
        $filename .= $exifData["Model"] ?? "NoModel";
        $filename .= "_";
        $filename .= $file->getClientOriginalName();

        $fileContent = file_get_contents($file->getRealPath());

        $width = $exifData['COMPUTED']['Width'];
        $height = $exifData['COMPUTED']['Height'];

        $orientation = $exifData['Orientation'];

        if($orientation == 6 || $orientation == 8) {
            $tempW = $width;
            $width = $height;
            $height = $tempW;
        }

        $img = imagecreatefromstring($fileContent);
        $deg = 0;
        switch ($orientation) {
            case 3:
                $deg = 180;
                break;
            case 6:
                $deg = 270;
                break;
            case 8:
                $deg = 90;
                break;
        }

        if($deg) {
            //ini_set("memory_limit","2G");
            $img = imagerotate($img, $deg, 0);
        }


        ob_start();
        imagejpeg($img);
        $fileContent = ob_get_contents();
        ob_end_clean();

        imagedestroy($img);


        if($fs->has($filename)) {
            $fileStatuses[$file->getClientOriginalName()] = "successFileExists";
            $savedFiles[$filename] = array(
                "exif" => $exifData,
                "fileTime" => $fileTime,
                "originalName" => $file->getClientOriginalName(),
                "width" => $width,
                "height" => $height
            );
        } elseif($fs->write($filename, $fileContent)) {
            $savedFiles[$filename] = array(
                "exif" => $exifData,
                "fileTime" => $fileTime,
                "originalName" => $file->getClientOriginalName(),
                "width" => $width,
                "height" => $height
            );
            $fileStatuses[$file->getClientOriginalName()] = "success";
        } else {
            $fileStatuses[$file->getClientOriginalName()] = "errorFileSystem";
        }

        return array(
            "status" => $fileStatuses,
            "saved" => $savedFiles
        );
    }
}