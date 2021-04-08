<?php

namespace App\Utils\Extractor;

class JsonExtractor extends FileExtractor
{
    /**
     * @param string $fileName
     * @param string $targetDir
     *
     * @return array
     */
    public function getFormattedContent(string $fileName, string $targetDir): array
    {
        $fileContent = $this->getContent($fileName, $targetDir);

        return json_decode($fileContent, true);
    }
}
