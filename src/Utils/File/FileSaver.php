<?php

namespace App\Utils\File;

use App\Utils\FileSystem\FileSystemWorker;
use App\Utils\FileSystem\FolderWorker;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @var FileSystemWorker
     */
    private $fileSystemWorker;

    /**
     * @var string
     */
    private $uploadsTempDir;

    public function __construct(SluggerInterface $slugger, FileSystemWorker $fileSystemWorker, string $uploadsTempDir)
    {
        $this->slugger = $slugger;
        $this->fileSystemWorker = $fileSystemWorker;
        $this->uploadsTempDir = $uploadsTempDir;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string
     */
    public function saveUploadedFileIntoTemp(UploadedFile $uploadedFile)
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);

        $fileName = sprintf('%s-%s.%s', $safeFilename, uniqid(), $uploadedFile->guessExtension());

        $this->fileSystemWorker->createFolderIfNotExist($this->uploadsTempDir);

        try {
            $uploadedFile->move($this->uploadsTempDir, $fileName);
        } catch (FileException $e) {
            return null;
        }

        return $fileName;
    }
}
