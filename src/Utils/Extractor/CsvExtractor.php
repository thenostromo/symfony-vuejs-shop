<?php

namespace App\Utils\Extractor;

class CsvExtractor extends FileExtractor
{
    /**
     * @param string $fileName
     * @param string $targetDir
     * @return array
     */
    public function getFormattedContent(string $fileName, string $targetDir): array
    {
        $fileContent = $this->getContent($fileName, $targetDir);

        $csvLines = explode(PHP_EOL, $fileContent);

        $result = [];
        foreach ($csvLines as $csvLine) {
            $result[] = str_getcsv($csvLine);
        }

        return $result;
    }
}
