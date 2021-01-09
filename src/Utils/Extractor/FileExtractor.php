<?php

namespace App\Utils\Extractor;

use App\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class FileExtractor
{
    /**
     * @param string $fileName
     * @param string $targetDir
     * @return string
     */
    public function getContent(string $fileName, string $targetDir): string
    {
        $filePath = sprintf('%s/%s', $targetDir, $fileName);
        $filesystem = new Filesystem();

        if (!$filesystem->exists($filePath)) {
            throw new FileNotFoundException();
        }

        $file = new SplFileInfo($filePath, '', '');

        return $file->getContents();
    }
}
