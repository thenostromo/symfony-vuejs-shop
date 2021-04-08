<?php

namespace App\Utils\File;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageResizer
{
    /**
     * @var Imagine
     */
    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function resizeImageAndSave(string $originalFileFolder, string $originalFileName, $targetParams)
    {
        $originalFilePath = $originalFileFolder.'/'.$originalFileName;

        list($iwidth, $iheight) = getimagesize($originalFilePath);

        $ratio = $iwidth / $iheight;
        $width = $targetParams['width'];
        $height = $targetParams['height'];
        if ($height) {
            if ($width / $height > $ratio) {
                $width = $height * $ratio;
            } else {
                $height = $width / $ratio;
            }
        } else {
            $height = $width / $ratio;
        }

        $targetFolder = $targetParams['newFolder'];
        $newFileName = $targetParams['newFileName'];

        // ТУТ УБРАТЬ JPG
        $newFilePath = sprintf('%s/%s', $targetFolder, $newFileName);

        $photo = $this->imagine->open($originalFilePath);
        $photo->resize(new Box($width, $height))->save($newFilePath);

        return $newFileName;
    }
}
