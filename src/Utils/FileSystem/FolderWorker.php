<?php

namespace App\Utils\FileSystem;

use Symfony\Component\Filesystem\Filesystem;

class FolderWorker
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * @param string $folder
     * @return string
     */
    public function createFolderIfNotExist(string $folder)
    {
        if (!$this->filesystem->exists($folder)) {
            $this->filesystem->mkdir($this->$folder);
        }
    }
}